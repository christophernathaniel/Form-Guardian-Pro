const buttons = [
    { name: 'Add Header', code: '<h1>Your Header Here</h1>' },
    { name: 'Add Paragraph', code: '<p>Your paragraph text here</p>' },
    { name: 'Add List', code: '<ul><li>List item 1</li><li>List item 2</li></ul>' },
    { name: 'Add Firstname', code: '<label><span> First Name <span class="required">*</span> <input maxlength="30" name="first-name" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter First Name" /> </label>' },
    { name: 'Add Lastname', code: '<label> Last Name <span class="required">*</span> <input maxlength="30" name="last-name" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Last Name" /> </label> ' },
    { name: 'Add Email', code: '<label> Email <span class="required">*</span> <input maxlength="30" name="email" pattern="^[a-zA-Z]+$" type="email" value="" placeholder="Enter Email" /> </label>' },
    { name: 'Add Phone', code: '<label> Phone <span class="required">*</span> <input maxlength="30" name="phone" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Phone" /> </label>' },
    { name: 'Add Address', code: '<label> Address <span class="required">*</span> <input maxlength="30" name="address" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Address" /> </label>' },
    { name: 'Add City', code: '<label> City <span class="required">*</span> <input maxlength="30" name="city" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter City" /> </label>' },
    { name: 'Add State/Region', code: '<label> State <span class="required">*</span> <input maxlength="30" name="state" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter State" /> </label>' },
    { name: 'Add Zip/Postcode', code: '<label> Zip <span class="required">*</span> <input maxlength="30" name="zip" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Zip" /> </label>' },
    { name: 'Add Country', code: '<label> Country <span class="required">*</span> <input maxlength="30" name="country" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Country" /> </label>' },
    { name: 'Add Message', code: '<label> Message <span class="required">*</span> <textarea maxlength="30" name="message" pattern="^[a-zA-Z]+$" type="text" value="" placeholder="Enter Message" /> </label>' },
    { name: 'Add GDPR', code: '<label> <input type="checkbox" name="gdpr" required="" /> I agree to the terms and conditions </label>' },
    { name: 'Add Newsletter', code: '<label> <input type="checkbox" name="newsletter" required="" /> I would like to receive the newsletter </label>'}
];


    let mediaButton = document.querySelector('#wp-content-editor-tools'); 
    if (mediaButton) {
        buttons.forEach(function(button) {
            let customButton = document.createElement('button');
            customButton.style.marginLeft = '5px'; 
            customButton.innerHTML = button.name;
            customButton.classList.add('button');
            customButton.style.alignItems = 'center';
            customButton.style.display = 'inline-flex';
            customButton.style.justifyContent = 'center';
            customButton.style.marginBottom = '10px';

            let customSpan = document.createElement('span');
            customSpan.classList.add('dashicons', 'dashicons-plus'); 
            customSpan.style.marginLeft = '5px'; 
            customSpan.style.marginTop = '2px';


            customButton.onclick = function (e) {
                e.preventDefault();
                var editor = tinyMCE.activeEditor;
                if (editor && !editor.isHidden()) {
                    editor.insertContent(button.code); 
                } else {
                    var textarea = document.getElementById('content');
                    textarea.value += button.code;
                }
            };


            customButton.appendChild(customSpan);
            mediaButton.appendChild(customButton);
          
        });
    }
