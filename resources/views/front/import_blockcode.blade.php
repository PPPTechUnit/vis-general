<form action="{{ route('blockcode.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="files[]" multiple><BR>
    <input type="submit" value="IMPORT">
</form>
