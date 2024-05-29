const buttons = [
    { name: 'Add Header', code: '<h1>Your Header Here</h1>' },
    { name: 'Add Paragraph', code: '<p>Your paragraph text here</p>' },
    { name: 'Add List', code: '<ul><li>List item 1</li><li>List item 2</li></ul>' }
];


    var mediaButton = document.querySelector('#wp-content-editor-tools'); // Adjust the selector as needed
    if (mediaButton) {
        buttons.forEach(function(button) {
            var customButton = document.createElement('button');
            customButton.style.marginLeft = '5px'; // Add some margin for spacing
            customButton.classList.add('button', 'insert-media', 'add_media');
            customButton.innerHTML = button.name;
            customButton.classList.add('button', 'insert-media', 'add_media');
            customButton.style.alignItems = 'center';
            customButton.style.display = 'inline-flex';
            customButton.style.justifyContent = 'center';
            var customSpan = document.createElement('span');
            
            customSpan.classList.add('dashicons', 'dashicons-plus'); // Add a plus icon
            customSpan.style.marginLeft = '5px'; // Add some margin for spacing
            customSpan.style.marginTop = '2px'; // Adjust the vertical alignment


            customButton.onclick = function (e) {
                e.preventDefault();
                var editor = tinyMCE.activeEditor;
                if (editor && !editor.isHidden()) {
                    editor.insertContent(button.code); // Use the code from the button object
                } else {
                    // Fallback for inserting text directly into the textarea
                    var textarea = document.getElementById('content');
                    textarea.value += button.code;
                }
            };

            // Append the new button after the previous button
            customButton.appendChild(customSpan);
            mediaButton.appendChild(customButton);
          
        });
    }
