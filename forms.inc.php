<?php

// This script defines the function create_form_input(), used in Lab 3

// This function generates a form INPUT tag.
// It takes three arguments:
// - The name to be given to the element.
// - The type of element (text, password).
// - An array of errors to be shown in the form.

function create_form_input($name, $type, $errors) 
{
	// Assume no value already exists:
	$value = false;
	
	// Check for a value in POST:
	if (isset($_POST[$name])) $value = $_POST[$name];
	
	// Strip slashes if Magic Quotes is enabled:
	if ($value && get_magic_quotes_gpc()) $value = stripslashes($value);

	// Conditional to determine what kind of element to create:
	if ( ($type == 'text') || ($type == 'password') ) 
	{   // Create text or password inputs.
		
	    // Start creating the input:
	    echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '"';
		
	    // Add the value to the input:
	    if ($value) 
	    {
            echo ' value="' . htmlspecialchars($value) . '"';
		}
	}

	// Check for an error:
	if (array_key_exists($name, $errors)) 
    {
	    echo 'class="error" /> <span class="error">' . $errors[$name] . '</span>';
	} 
    else 
    {
        echo ' />';		
	}
		 
} // End of the create_form_input() function.
