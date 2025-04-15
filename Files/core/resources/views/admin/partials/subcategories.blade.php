@if ($subcategory->allSubcategories)
    @php $prefix .='|--'  @endphp
    @foreach ($subcategory->allSubcategories as $childCategory)
        <option value="{{ $subcategory->id }}" data-title="{{ __($childCategory->name) }}"> {{ $prefix }} @lang($childCategory->name)</option>
        @include('admin.partials.subcategories', ['subcategory' => $childCategory])
    @endforeach
@endif
