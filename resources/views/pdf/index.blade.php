<form action="{{route('send-pdf')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="images">Upload Images:</label>
    <input type="file" name="images[]" multiple>
    <label for="email">Recipient Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send PDF</button>
</form>
