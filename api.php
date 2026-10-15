<html>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<table id='photo-table'>
  <thead>
    <tr><th>File</th><th>Description</th><th>User</th><th>Date</th></tr>
  </thead>
  <tbody id='tbody'></tbody>
</table>
 
<!-- JS -->
<script>
  $.ajax({
    url: 'https://alihaiderdev.online/portfolio/get_public_photo.php',
    type: 'GET',
    dataType: 'json',
    success: function(photos) {
      $.each(photos, function(i, photo) {
        var row = '<tr>' +
          '<td><img src="https://alihaiderdev.online/portfolio/images/' + photo.file + '" style="width:80px; height:80px; object-fit:cover; border-radius:6px;"></td>' +
          '<td>' + photo.description + '</td>' +
          '<td>' + photo.username    + '</td>' +
          '<td>' + photo.created_at  + '</td>' +
        '</tr>';
        $('#tbody').append(row);
      });
    }
  });
</script>
</html>