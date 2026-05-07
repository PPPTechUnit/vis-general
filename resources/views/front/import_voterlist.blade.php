<h1>IMPORT VOTERLIST</h1>
<form action="{{ route('importvoters.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="files[]" multiple><BR><BR>
    <input type="submit" value="IMPORT">
</form>
