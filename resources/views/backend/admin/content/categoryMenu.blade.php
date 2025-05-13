<a href="#" class="category-item" data-id="{{ $category->id }}">
    <i class="fas fa-folder"></i> {{ $category->category_name }}
</a>

@if ($category->children->count())
    <div class="sub-menu">
        @foreach($category->children as $child)
            @include('backend.admin.content.categoryMenu', ['category' => $child])
        @endforeach
    </div>
@endif