<div class="category-item" data-id="{{ $category->id }}">
    <span>
        {{ $category->category_name }}
    </span>

    @if ($category->children->count())
        <i class="las la-chevron-right arrow"></i>
    @endif

    @if ($category->children->count())
        <div class="sub-menu">
            @foreach($category->children as $child)
                @include('backend.admin.content.categoryMenu', ['category' => $child])
            @endforeach
        </div>
    @endif
</div>