function ShowModalDelete(admin, increment_value)
{
  if (confirm("Хотите удалить этот элемент?")) 
      document.getElementById("tform").src = GreateLink(admin, 'delete', 'increment', increment_value);
}