jQuery(document).ready(function($) {
    
    // AddToCart Triggers Repeater
    var atcIndex = parseInt($('#addtocart-triggers-repeater .st-repeater-row').last().data('index') || '0');
    
    $('#add-addtocart-trigger').on('click', function() {
        atcIndex++;
        var row = '<div class="st-repeater-row" data-index="' + atcIndex + '">' +
            '<div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 10px; margin-bottom: 10px; align-items: center;">' +
            '<input type="text" name="eventrix_addtocart_triggers[' + atcIndex + '][value]" value="" placeholder="ajax_add_to_cart" class="st-input" />' +
            '<select name="eventrix_addtocart_triggers[' + atcIndex + '][type]" class="st-select">' +
            '<option value="class">class</option>' +
            '<option value="name">name</option>' +
            '<option value="id">id</option>' +
            '<option value="href">href</option>' +
            '<option value="data-attribute">data-attribute</option>' +
            '</select>' +
            '<button type="button" class="st-btn-remove st-repeater-remove" style="padding: 5px 10px; background: #dc3232; color: white; border: none; cursor: pointer;">Delete</button>' +
            '</div>' +
            '</div>';
        $('#addtocart-triggers-repeater').append(row);
    });
    
    // Custom Events Repeater
    var ceIndex = parseInt($('#custom-events-repeater .st-repeater-row').last().data('index') || '-1');
    
    $('#add-custom-event').on('click', function() {
        ceIndex++;
        var row = '<div class="st-repeater-row" data-index="' + ceIndex + '">' +
            '<div style="display: grid; grid-template-columns: 2fr 2fr 1fr auto; gap: 10px; margin-bottom: 10px; align-items: center;">' +
            '<input type="text" name="eventrix_custom_events[' + ceIndex + '][custom_url]" value="" placeholder="/cart" class="st-input" />' +
            '<input type="text" name="eventrix_custom_events[' + ceIndex + '][custom_event_name]" value="" placeholder="cart" class="st-input" />' +
            '<input type="number" name="eventrix_custom_events[' + ceIndex + '][value]" value="0" placeholder="0" step="0.01" class="st-input" />' +
            '<button type="button" class="st-btn-remove st-repeater-remove" style="padding: 5px 10px; background: #dc3232; color: white; border: none; cursor: pointer;">Delete</button>' +
            '</div>' +
            '</div>';
        $('#custom-events-repeater').append(row);
    });
    
    // Remove row handler (delegated event)
    $(document).on('click', '.st-repeater-remove', function() {
        $(this).closest('.st-repeater-row').remove();
    });
    
});
