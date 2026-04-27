<script>
function editMessage(id, message, senderType) {
  document.getElementById('message-content-' + id).style.display = 'none';
  document.getElementById('message-edit-' + id).style.display = 'block';
}

function cancelEdit(id) {
  document.getElementById('message-content-' + id).style.display = 'block';
  document.getElementById('message-edit-' + id).style.display = 'none';
}
</script>
