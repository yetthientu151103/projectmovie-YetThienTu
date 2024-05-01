<select name="movie_category">
    @foreach($movieCategories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
    @endforeach
</select>
