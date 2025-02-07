/**
 * 
 * dynamic-forms.js
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

const dynamicFormModule = (() => {
    const module = {

        loadEvents: function() {
            // remove events, subsequent calls will add onAdd/onRemove to be called multiple times on 1 button click
            $('.dynamic-form-button-add').off('click')
            $('.dynamic-form-button-remove').off('click')
            // add them back
            $('.dynamic-form-button-add').on('click', function() {
                dynamicFormModule.onAdd(this)
            })
            $('.dynamic-form-button-remove').on('click', function() {
                dynamicFormModule.onRemove(this)
            })
        },
        updateRemoveButtonState: function(section) {
            if ($(section).find('.dynamic-form-input-group').length <= 1) {
                // disable remove button if there is only 1 element
                $(section).find('.dynamic-form-button-remove').prop('disabled', true)
            } else {
                // otherwise, keep enabled
                $(section).find('.dynamic-form-button-remove').prop('disabled', false)
            }
        },
        onAdd: function (theAddButton) {
            // $(this) will point to whatever event an element delegated this function to
            // ; in this case, in loadEvents, .dynamic-form-button-add elements.
            const $parent = $(theAddButton).closest('.dynamic-form-input-group')
            const $clone = $parent.clone()
            // append the clone
            const $section = $parent.closest('.dynamic-form')
            $section.append($clone)
            // update input numbers.
            // copying the <select> elements wont select the same <option>s, but this is okay especially for the TimeEntry form.
            $clone.find('input, select, textarea').each(function() {
                const name = $(this).attr('name')
                // match a string like "[123]", where 123 is any number
                const match = name.match(/\[(\d+)\]/)
                if (match) {
                    const newIndex = parseInt(match[1], 10) + 1;
                    $(this).attr('name', name.replace(/\[\d+\]/, `[${newIndex}]`))
                }
            })
            // reload events so we register the new buttons we just added
            this.loadEvents()
            this.updateRemoveButtonState($section)
        },
        onRemove: function (theRemoveButton) {
            const $parent = $(theRemoveButton).closest('.dynamic-form-input-group')
            const $section = $parent.closest('.dynamic-form')
            if ($section.find('.dynamic-form-input-group').length > 1) {
                $parent.remove()
            } else {
                alert('Cannot remove the last entry!')
            }
            // reload events so we register the new buttons we just added
            this.loadEvents()
            this.updateRemoveButtonState($section)
        }

    }

    // allow jquery to load
    jQuery(() => module.loadEvents())

    return module
    
})()

export default dynamicFormModule