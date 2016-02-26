<select class="form-control">
    @foreach ($tags as $tag)
        <option>{{ $tag->GroupName }}</option>
    @endforeach
</select>

<script type="text/javascript">
    $('select').select2();
</script>
