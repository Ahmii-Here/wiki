@if(count($entities) > 0)
<div class="entity-list {{ $style ?? '' }}">
    @foreach($entities as $index => $entity)
    <?php $type = $entity->getType(); ?>
    <div class="entity-wrapper">
        <a href="{{ $entity->getUrl() }}" class="{{$type}} {{$type === 'page' && $entity->draft ? 'draft' : ''}} {{$classes ?? ''}} entity-list-item" data-entity-type="{{$type}}" data-entity-id="{{$entity->id}}">
            <span role="presentation" class="icon text-bookshelf">@icon('bookshelf')</span>
            <div class="content">
                <h4 class="entity-list-item-name break-text">{{ $entity->preview_name ?? $entity->name }}</h4>
                {{ $slot ?? '' }}
            </div>
        </a>

        <div class="container">
    <div class="row justify-content-center">
        <div class="col-8"> <!-- Adjust the column width as needed -->
            <a href="javascript:void(0);" class="btn btn-primary toggle-button" style="font-size: small;" data-target="entity-{{$entity->id}}">@icon('caret-down')expand</a>
        </div>
    </div>
</div>

        <div id="entity-{{$entity->id}}" class="books-list" style="display: none; margin-left:10px;">
            @if(count($entity->books) > 0)
            @foreach($entity->books as $book)
            <a href="{{ $book->getUrl() }}" class="{{$type}} {{$type === 'page' && $entity->draft ? 'draft' : ''}} {{$classes ?? ''}} entity-list-item" data-entity-type="{{$type}}" data-entity-id="{{$entity->id}}">
                <span role="presentation" class="icon text-book">@icon('book')</span>
                <div class="content">
                    <h4 class="entity-list-item-name break-text">{{ $book->name }}</h4>
                    {{ $slot ?? '' }}
                </div>
            </a>
            @endforeach
            @else
            <p class="text-muted empty-text pb-xl mb-none text-center">
                {{ $emptyText ?? trans('No Books Available') }}
            </p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<p class="text-muted empty-text pb-l mb-none">
    {{ $emptyText ?? trans('common.no_items') }}
</p>
@endif

<script nonce="{{$cspNonce}}">
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-button').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);
                if (targetElement.style.display === "none") {
                    targetElement.style.display = "block";
                } else {
                    targetElement.style.display = "none";
                }
            });
        });
    });
</script>