/**
 * 
 * DynamicForm.js
 * 
 * Handles dynamic form generation
 * 
 */

{/* 
// '...' indicates anything you want
// .dynamic-form is usually only created once in a <form> tag, but multiple forms can be on a page

<div id="..." class="dynamic-form ...">
    <div class="dynamic-form-input-group ...">
        ... // your inputs

        // these are the add/remove buttons, they can be in their own div if required
        <?= Html::button('Remove', ['class' => 'dynamic-form-button-remove ...']); ?>
        <?= Html::button('Add', ['class' => 'dynamic-form-button-add ...']); ?>
    </div>
</div> 
*/}

/**
 * Handler to easily set up dynamic add/remove forms
 */
const DynamicForm = (() => {
    const module = {
        loadEvents: function() {
            // remove events, subsequent calls will add onAdd/onRemove to be called multiple times on 1 button click
            $('.dynamic-form-button-add').off('click')
            $('.dynamic-form-button-remove').off('click')
            // add them back
            $('.dynamic-form-button-add').on('click', function() {
                module.onAdd($(this).closest('.dynamic-form-input-group'))
            })
            $('.dynamic-form-button-remove').on('click', function() {
                module.onRemove($(this).closest('.dynamic-form-input-group'))
            })
            $('.dynamic-form').each((_, element) => {
                this.updateRemoveButtonState(element)
            })
        },
        updateRemoveButtonState: function(theForm) {
            if ($(theForm).find('.dynamic-form-input-group').length <= 1) {
                // disable remove button if there is only 1 element
                $(theForm).find('.dynamic-form-button-remove').prop('disabled', true)
            } else {
                // otherwise, keep enabled
                $(theForm).find('.dynamic-form-button-remove').prop('disabled', false)
            }
        },
        onAdd: function (theParent) {
            const clone = $(theParent).clone()
            // for any clone element without the .dynamic-form-clone-value class, drop their value
            $(clone).find(`input:not([type="radio"]):not(.dynamic-form-clone-value)`).each(function(_, element) {
                $(element).val('')
            })
            $(clone).find(`input[type="radio"]:not(.dynamic-form-clone-value)`).each(function(_, element) {
                $(element).prop('checked', false)
            })
            // append the clone
            const form = $(theParent).closest('.dynamic-form')
            // update input numbers.
            $(clone).find('input, select, textarea').each(function() {
                const name = $(this).attr('name')
                // match a string like "[123]", where 123 is any number, and increment it
                const regex = /\[(\d+)\]/
                const match = name.match(regex)
                if (match) {
                    const newIndex = parseInt(match[1], 10) + 1;
                    $(this).attr('name', name.replace(regex, `[${newIndex}]`))
                }
            })
            // append after we update the indexessince it can screw up radio buttons
            $(form).append($(clone))
            // reload events so we register the new buttons we just added
            this.loadEvents()
            this.updateRemoveButtonState($(form))
        },
        onRemove: function (theParent) {
            const form = $(theParent).closest('.dynamic-form')
            if ($(form).find('.dynamic-form-input-group').length > 1) {
                $(theParent).remove()
            } else {
                alert('Cannot remove the last entry!')
            }
            // reload events so we register the new buttons we just added
            this.loadEvents()
            this.updateRemoveButtonState($(form))
        }

    }

    // allow jquery to load
    jQuery(() => module.loadEvents())

    return module
    
})

export default DynamicForm