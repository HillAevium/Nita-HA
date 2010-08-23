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
    
    this.onAddProfile = function() {
        $("#profile_form").resetForm();
        $("#profile_form").attr("action", "/account/profile_add");
        this.onEdit("#profile_form");
    };
    
    this.onEdit = function(form, callback) {
        $(bindings.container).hide();
        $("#forms_container").show();
        $(form).show();
        
        $(document).ajaxComplete(function(e, xhr, setting) {
            switch(xhr.status) {
                case 202 : // ACCEPTED
                case 201 : // CREATED
                    if(callback != undefined) {
                        callback();
                    }
                    window.location.reload();
                    break;
                case 400 : // BAD_REQUEST
                    $("#error_container").html(xhr.responseText);
                    break;
            }
        });
        
        $("#continue").click(function() {
            $(form).ajaxSubmit();
            return false;
        });
        $("#back").click(function() {
            controller.tearDown();
            $(bindings.container).show();
            return false;
        });
    };
    
    this.onEditProfile = function(id) {
        if(profiles != undefined) {
            $("#profile_form").append('<input type="hidden" name="id" value="'+profiles[id].id+'" />');
            injectProfile(profiles[id]);
        }
        $("#profile_form").attr("action", "/account/profile");
        this.onEdit("#profile_form", function() {
            $('#profile_form input[name="id"]').remove();
        });
    };
    
    this.onEditAccount = function() {
        this.onEdit("#firm_form");
    };
    
    this.tearDown = function() {
        $("#firm_form").hide();
        $("#profile_form").hide();
        $("#forms_container").hide();
        $(bindings.container).hide();
        $("#back").unbind('click');
        $("#continue").unbind('click');
        $(document).unbind('ajaxComplete');
        $("#error_container").html('');
        $("#response_message").html('');
    };
}

function View() {
    this.dom = {};
    this.currentPane = null;
    this.currentButton = null;
    
    this.bind = function() {
        $("#add_profile").click(function() {
            controller.onAddProfile();
            return false;
        });
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