// Grab the number in the edit button id
// Select the text with the same id number as the edit button
// Turn the text into a contenteditable paragraph
// Turn the edit button into a save button
const editButtons = document.querySelectorAll('[id^="btn-edit"]');
editButtons.forEach(button => {
  const id = button.id.split('btn-edit-')[1];
  const textField = document.querySelector(`p#text-${id}`);
  const originalText = textField.textContent;
  button.addEventListener('click', () => toggleEditMode(id, textField, button, originalText));
});

function toggleEditMode(id, textField, button, originalText) {

  if (button.textContent == 'Save') {
    textField.setAttribute('contenteditable', false);
    button.textContent = 'Edit';
    button.classList.remove('btn-success');
    button.classList.add('btn-light');
    const newText = textField.textContent;
    saveMessageContent(id, newText, originalText, textField);
  } else {
    textField.setAttribute('contenteditable', true);
    button.textContent = 'Save';
    button.classList.remove('btn-light');
    button.classList.add('btn-success');
    textField.focus();
  }
}

// WHEN SAVED
// Send the new text to the database
// Check the database response
// Remove the contenteditable attr
// Keep the edited text if correctly saved
// Render the original text if not saved correctly
function saveMessageContent(id, updated, original, target) {
  // console.log('id: ', id);
  // console.log('updated: ', updated);
  // console.log('original: ', original);
  // console.log('target: ', target);

  const data = new FormData();
  data.append('msgID', id);
  data.append('msg_txt', updated);

  fetch("https://subatomic.cse361-spring2023.com/aux/update_msg.php", {
    'method': 'POST',
    'body': data,
    'headers': {"Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "PUT, GET, POST, OPTIONS",
                "Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept"}
  })
  .then(res => {
    console.log(res);
    if (res.status !== 200) {
      target.textContent = original;
    }
  });
}
