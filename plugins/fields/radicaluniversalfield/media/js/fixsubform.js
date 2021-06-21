document.addEventListener('DOMContentLoaded' ,function () {

    function fixSubformTrigger(event_name, el) {
        let event;
        if (document.createEvent) {
            event = document.createEvent("HTMLEvents");
            event.initEvent(event_name, true, true);
            event.eventName = event_name;
            el.dispatchEvent(event);
        } else {
            event = document.createEventObject();
            event.eventName = event_name;
            event.eventType = event_name;
            el.fireEvent("on" + event.eventType, event);
        }
    }

    if (window.jQuery !== undefined) {
        jQuery(document).on('subform-row-add', function (event, row) {
            fixSubformTrigger('DOMContentLoaded', row);
        });
    }

});