var controller;
var profiles;
/* bindings
 * buttonBar: (jQuery Selector)
 * container: (jQuery Selector)
 * panels: (Array(Object))
 *     button: (jQuery Selector)
 *     pane:   (jQuery Selector)
 */

function ProfileController(bindings) {
    this.view = null;
    this.bindings = bindings;
    
    this.go = function() {
        this.view = new View();
        this.view.bind();
    };
    
    this.onShowPane = function(button, pane) {
        this.view.togglePane(this.view.currentButton, this.view.currentPane);
        this.view.togglePane(button, pane);
        this.view.currentButton = button;
        this.view.currentPane = pane;
    };
    
    this.onEdit = function(form) {
        $(bindings.container).hide();
        $("#forms_container").show();
        $(form).show();
        
        $(document).ajaxComplete(function(e, xhr, setting) {
            switch(xhr.status) {
                case 202 : // ACCEPTED
                    $(form).hide();
                    $("#forms_container").hide();
                    $(bindings.container).show();
                    $(document).unbind('ajaxComplete');
                    $("#submit_form").unbind('click');
                    break;
                case 400 : // BAD_REQUEST
                    $("#error_container").html(xhr.responseText);
                    break;
            }
        });
        
        $("#submit_form").click(function() {
            $(form).ajaxSubmit();
        });
    };
    
    this.onEditProfile = function(id) {
        this.onEdit("#profile_form");
        if(profiles != undefined) {
            injectProfile(profiles[id]);
        }
    };
    
    this.onEditAccount = function() {
        this.onEdit("#firm_form");
    };
}

function View() {
    this.dom = {};
    this.currentPane = null;
    this.currentButton = null;
    
    this.bind = function() {
        this.dom.buttonBar = $(controller.bindings.buttonBar);
        this.dom.container = $(controller.bindings.controller);
        var trigger = controller.bindings.trigger;
        var panels = controller.bindings.panels;
        for(var i in panels) {
            var button = $(panels[i].button);
            var pane = $(panels[i].pane);
            if(this.currentPane == null) {
                this.currentPane = pane;
                this.currentButton = button;
                button.addClass('current');
                button.unbind(trigger);
                pane.show();
            } else {
                pane.hide();
                button.removeClass('current');
                button.bind(trigger, {button: button, pane: pane}, function(event) {
                    controller.onShowPane(event.data.button, event.data.pane);
                });
            }
        }
        
        
    };
    
    this.togglePane = function(button, pane) {
        var trigger = controller.bindings.trigger;
        if(button.hasClass('current')) {
            button.bind(trigger, {button: button, pane: pane}, function(event) {
                controller.onShowPane(event.data.button, event.data.pane);
            });
        } else {
            button.unbind(trigger);
        }
        button.toggleClass('current');
        pane.slideToggle(controller.bindings.speed);
        
    };
}