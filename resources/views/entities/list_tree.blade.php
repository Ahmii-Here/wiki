@if(count($entities) > 0)
    <ul class="sidebar-navigation">
        @foreach($entities as $space)
            <li class="entity space">
                <a href="javascript:void(0)" class="space entity-link toggle">
                    <span role="presentation" class="icon">@icon('bookshelf')</span>
                    <span class="entity-name">{{ $space->name }}</span>
                </a>
                @if(count($space->books) > 0)
                    <ul class="nested-entities books">
                        @foreach($space->books as $book)
                            <li class="entity book">
                                <a href="{{ $book->getUrl() }}" class="book entity-link">
                                    <span role="presentation" class="icon">@icon('book')</span>
                                    <span class="entity-name">{{ $book->name }}</span>
                                </a>
                                <div class="toggle-arrow" onclick="toggleEntity(this)"></div>
                                @if(count($book->chapters) > 0)
                                    <ul class="nested-entities chapters">
                                        @foreach($book->chapters as $chapter)
                                            <li class="entity chapter">
                                                <a href="{{ $chapter->getUrl() }}" class="chapter entity-link">
                                                    <span role="presentation" class="icon">@icon('chapter')</span>
                                                    <span class="entity-name">{{ $chapter->name }}</span>
                                                </a>
                                                @if(count($chapter->pages) > 0)
                                                    <ul class="nested-entities pages">
                                                        @foreach($chapter->pages as $page)
                                                            <li class="entity page">
                                                                <a href="{{ $page->getUrl() }}" class="page entity-link">
                                                                    <span role="presentation" class="icon">@icon('page')</span>
                                                                    <span class="entity-name">{{ $page->name }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if(count($book->directPages) > 0) <!-- Direct pages under the book -->
                                    <ul class="nested-entities pages">
                                        @foreach($book->directPages as $directPage)
                                            <li class="entity page">
                                                <a href="{{ $directPage->getUrl() }}" class="page entity-link">
                                                    <span role="presentation" class="icon">@icon('page')</span>
                                                    <span class="entity-name">{{ $directPage->name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted empty-text">{{ $emptyText ?? trans('common.no_items') }}</p>
@endif

<style>
    :root {
        --docspress-color-text: #333; /* Default text color */
        --docspress-color-primary: #1a73e8; /* Primary color for hover and active links */
        --docspress--navigation--padding: 10px 1px 10px 10px;
        --docspress--navigation--font-size: 0.85em;
        --docspress--navigation--color: var(--docspress-color-text);
        --docspress--navigation--link--padding: 3px 20px;
        --docspress--navigation--link--font-weight: 500;
        --docspress--navigation--link--color: inherit;
        --docspress--navigation--link-hover--color: var(--docspress-color-primary);
        --docspress--navigation--link-active--color: var(--docspress-color-primary);
        --docspress--navigation--link-active--font-weight: 500;
        --docspress--navigation--link-parent--padding: 7px 0;
        --docspress--navigation--link-children--font-weight: 400;
        --docspress--navigation--category--padding: 15px 0 7px 0;
        --docspress--navigation--category--font-weight: 700;
        --docspress--navigation--children--padding: 0 0 10px;
        --docspress--navigation--children--margin-left: 15px;
    }
   .sidebar-navigation, 
    .sidebar-navigation ul, 
    .sidebar-navigation ul li, 
    .sidebar-navigation .nested-entities, 
    .sidebar-navigation .nested-entities li {
        list-style-type: none !important; /* Removes bullets from all lists, ensuring override */
    }

    .sidebar-navigation .entity-link,
    .sidebar-navigation .entity-name {
        white-space: nowrap; /* Prevents text from wrapping */
        text-overflow: ellipsis; /* Adds an ellipsis if the text overflows */
    }

    .sidebar-navigation {
        padding: var(--docspress--navigation--padding);
        margin: 0;
        font-size: var(--docspress--navigation--font-size);
        color: var(--docspress--navigation--color);
        list-style: none;
    }

    .sidebar-navigation .entity .toggle {
        padding: var(--docspress--navigation--link-parent--padding);
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: var(--docspress--navigation--link--font-weight);
    }

    .sidebar-navigation .entity .entity-link {
        text-decoration: none;
        padding: var(--docspress--navigation--link--padding);
        color: var(--docspress--navigation--link--color);
        display: flex;
        align-items: center;
        transition: color 0.3s;
    }

    .sidebar-navigation .entity .entity-link:hover {
       color: var(--docspress--navigation--link-hover--color);
    }
 
    .    .sidebar-navigation, .nested-entities {
        list-style-type: none; /* Removes bullets from all lists */
        padding-left: 0; /* Removes indentation for the list */
    }


    .expanded .toggle-arrow:before {
        transform: rotate(180deg);
    }
</style>



<script nonce="{{ $cspNonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle the display of nested entities
        document.querySelectorAll('.sidebar-navigation .toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                let nextUl = toggle.nextElementSibling;
                if (nextUl && nextUl.classList.contains('nested-entities')) {
                    nextUl.style.display = nextUl.style.display === 'block' ? 'none' : 'block';
                    toggle.classList.toggle('expanded'); // Toggle the class to rotate the arrow
                }
            });
        });
    });
</script>
