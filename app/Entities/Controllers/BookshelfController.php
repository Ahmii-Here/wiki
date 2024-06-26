<?php

namespace BookStack\Entities\Controllers;

use BookStack\Activity\ActivityQueries;
use BookStack\Activity\Models\View;
use BookStack\Entities\Models\Book;
use BookStack\Entities\Repos\BookshelfRepo;
use BookStack\Entities\Tools\ShelfContext;
use BookStack\Exceptions\ImageUploadException;
use BookStack\Exceptions\NotFoundException;
use BookStack\Http\Controller;
use BookStack\References\ReferenceFetcher;
use BookStack\Util\SimpleListOptions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use BookStack\Entities\Repos\PageRepo;

class BookshelfController extends Controller
{
    public function __construct(
        protected BookshelfRepo $shelfRepo,
        protected ShelfContext $shelfContext,
        protected PageRepo $pageRepo,
        protected ReferenceFetcher $referenceFetcher
    ) {
    }

    /**
     * Display a listing of bookshelves.
     */
    public function index(Request $request)
    {
        $view = setting()->getForCurrentUser('bookshelves_view_type');
        $listOptions = SimpleListOptions::fromRequest($request, 'bookshelves')->withSortOptions([
            'name'       => trans('common.sort_name'),
            'created_at' => trans('common.sort_created_at'),
            'updated_at' => trans('common.sort_updated_at'),
        ]);

        $shelves = $this->shelfRepo->getAllPaginated(18, $listOptions->getSort(), $listOptions->getOrder());
        $recents = $this->isSignedIn() ? $this->shelfRepo->getRecentlyViewed(4) : false;
        $popular = $this->shelfRepo->getPopular(4);
        $new = $this->shelfRepo->getRecentlyCreated(4);

        $this->shelfContext->clearShelfContext();
        $this->setPageTitle(trans('entities.shelves'));

        return view('shelves.index', [
            'shelves'     => $shelves,
            'recents'     => $recents,
            'popular'     => $popular,
            'new'         => $new,
            'view'        => $view,
            'listOptions' => $listOptions,
        ]);
    }



    public function index_tree(Request $request)
    {
        $view = setting()->getForCurrentUser('bookshelves_view_type');
        $listOptions = SimpleListOptions::fromRequest($request, 'bookshelves')->withSortOptions([
            'name'       => trans('common.sort_name'),
            'created_at' => trans('common.sort_created_at'),
            'updated_at' => trans('common.sort_updated_at'),
        ]);

        $shelves = $this->shelfRepo->getAllPaginated(18, $listOptions->getSort(), $listOptions->getOrder());
        $recents = $this->shelfRepo->getRecentlyViewed(4);
        $popular = $this->shelfRepo->getPopular(4);
        $new = $this->shelfRepo->getRecentlyCreated(4);
        $left_space = $this->shelfRepo->getAll();
        // $left_space = $this->shelfRepo->getAll(['books.chapters.pages'], $listOptions->getSort(), $listOptions->getOrder());

        $this->shelfContext->clearShelfContext();
        $this->setPageTitle(trans('Tree View'));
        
        return view('shelves.index_tree', [
            'shelves'     => $shelves,
            'recents'     => $recents,
            'popular'     => $popular,
            'new'         => $new,
            'view'        => $view,
            'listOptions' => $listOptions,
            'left_space' => $left_space
        ]);
    }

    /**
     * Show the form for creating a new bookshelf.
     */
    public function create()
    {
        $this->checkPermission('bookshelf-create-all');
        $books = Book::visible()->orderBy('name')->get(['name', 'id', 'slug', 'created_at', 'updated_at']);
        $this->setPageTitle(trans('entities.shelves_create'));

        return view('shelves.create', ['books' => $books]);
    }

    /**
     * Store a newly created bookshelf in storage.
     *
     * @throws ValidationException
     * @throws ImageUploadException
     */
    public function store(Request $request)
    {
        $this->checkPermission('bookshelf-create-all');
        $validated = $this->validate($request, [
            'name'             => ['required', 'string', 'max:255'],
            'description_html' => ['string', 'max:2000'],
            'image'            => array_merge(['nullable'], $this->getImageValidationRules()),
            'tags'             => ['array'],
        ]);

        $bookIds = explode(',', $request->get('books', ''));
        $shelf = $this->shelfRepo->create($validated, $bookIds);

        return redirect($shelf->getUrl());
    }

    /**
     * Display the bookshelf of the given slug.
     *
     * @throws NotFoundException
     */
    public function show(Request $request, ActivityQueries $activities, string $slug)
    {
        $shelf = $this->shelfRepo->getBySlug($slug);
        $this->checkOwnablePermission('bookshelf-view', $shelf);
        

        $listOptions = SimpleListOptions::fromRequest($request, 'shelf_books')->withSortOptions([
            'default' => trans('common.sort_default'),
            'name' => trans('common.sort_name'),
            'created_at' => trans('common.sort_created_at'),
            'updated_at' => trans('common.sort_updated_at'),
        ]);

        $sort = $listOptions->getSort();
        $sortedVisibleShelfBooks = $shelf->visibleBooks()
            ->reorder($sort === 'default' ? 'order' : $sort, $listOptions->getOrder())
            ->get()
            ->values()
            ->all();

        View::incrementFor($shelf);
        $this->shelfContext->setShelfContext($shelf->id);
        $view = setting()->getForCurrentUser('bookshelf_view_type');
        $left_space = $this->shelfRepo->getAll();
        $this->setPageTitle($shelf->getShortName());

        return view('shelves.show', [
            'shelf'                   => $shelf,
            'sortedVisibleShelfBooks' => $sortedVisibleShelfBooks,
            'view'                    => $view,
            'activity'                => $activities->entityActivity($shelf, 20, 1),
            'listOptions'             => $listOptions,
            'referenceCount'          => $this->referenceFetcher->getReferenceCountToEntity($shelf),
            'left_space' => $left_space
        ]);
    }

    /**
     * Show the form for editing the specified bookshelf.
     */
    public function edit(string $slug)
    {
        $shelf = $this->shelfRepo->getBySlug($slug);
        $this->checkOwnablePermission('bookshelf-update', $shelf);

        $shelfBookIds = $shelf->books()->get(['id'])->pluck('id');
        $books = Book::visible()->whereNotIn('id', $shelfBookIds)->orderBy('name')->get(['name', 'id', 'slug', 'created_at', 'updated_at']);

        $this->setPageTitle(trans('entities.shelves_edit_named', ['name' => $shelf->getShortName()]));

        return view('shelves.edit', [
            'shelf' => $shelf,
            'books' => $books,
        ]);
    }

    /**
     * Update the specified bookshelf in storage.
     *
     * @throws ValidationException
     * @throws ImageUploadException
     * @throws NotFoundException
     */
    public function update(Request $request, string $slug)
    {
        $shelf = $this->shelfRepo->getBySlug($slug);
        $this->checkOwnablePermission('bookshelf-update', $shelf);
        $validated = $this->validate($request, [
            'name'             => ['required', 'string', 'max:255'],
            'description_html' => ['string', 'max:2000'],
            'image'            => array_merge(['nullable'], $this->getImageValidationRules()),
            'tags'             => ['array'],
        ]);

        if ($request->has('image_reset')) {
            $validated['image'] = null;
        } elseif (array_key_exists('image', $validated) && is_null($validated['image'])) {
            unset($validated['image']);
        }

        $bookIds = explode(',', $request->get('books', ''));
        $shelf = $this->shelfRepo->update($shelf, $validated, $bookIds);

        return redirect($shelf->getUrl());
    }

    /**
     * Shows the page to confirm deletion.
     */
    public function showDelete(string $slug)
    {
        $shelf = $this->shelfRepo->getBySlug($slug);
        $this->checkOwnablePermission('bookshelf-delete', $shelf);

        $this->setPageTitle(trans('entities.shelves_delete_named', ['name' => $shelf->getShortName()]));

        return view('shelves.delete', ['shelf' => $shelf]);
    }

    /**
     * Remove the specified bookshelf from storage.
     *
     * @throws Exception
     */
    public function destroy(string $slug)
    {
        $shelf = $this->shelfRepo->getBySlug($slug);
        $this->checkOwnablePermission('bookshelf-delete', $shelf);

        $this->shelfRepo->destroy($shelf);

        return redirect('/shelves');
    }
}
