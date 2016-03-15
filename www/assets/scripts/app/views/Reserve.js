ReserveView = BaseFormView.extend({
    id: 'reserve-wizard',
    className: 'listing',
    template_name: 'reserve-wizard',

    events: {
        'click div.step1 .btn-next' : 'step2',
        'click div.step2 .btn-next' : 'step3',
        'click div.step2 .btn-back' : 'step1',
        'click div.step3 .btn-next' : 'step2',
        'click div.step3 .btn-next' : 'submit',
        'click div.step3 .btn-back' : 'step2',
        'click button.btn-edit' : 'edit_step'
    },

    render: function () {
        BaseFormView.prototype.render.call(this);
        var me = this;

        this.$el.find('.toggle-label').click(function() {
            var input = $(this).closest('.type-select').find('input');
            if(input.attr('checked')) {
                input.removeAttr('checked');
            } else {
                input.attr('checked','checked');
            }
            return false;
        });

        this.$el.find('input.date').mask('00/00/0000');
        this.$el.find('input.money').mask('000,000,000', {reverse: true});
        this.$el.find('input.phone').mask('(000) 000-0000');

        this.$el.find('form').validate();
        this.$el.find('form').ajaxForm({
            success: function(responseText, statusText, xhr, form) {
                app.success_message('Your reservation has been received successfully.  We will get back to you shortly.', me.$el.find('.alert-container'));
            }
        });
        return this;
    },

    showStep : function(stepId) {

        var form = this.$el.find('form');
        if(form.valid()) {
            this.$el.find('div.step').hide();
            this.$el.find('ul.steps li').removeClass('active');
            this.$el.find('ul.steps li.step'+stepId).addClass('active');
            this.$el.find('div.step'+stepId).show();
        }
    },

    step1 : function(event) {
        this.showStep(1);
        return false;
    },

    step2 : function(event) {
        this.showStep(2);
        return false;
    },

    step3 : function(event) {
        var values = this.$el.find('form').serializeObject();
        for (var property in values) {
            if (values.hasOwnProperty(property)) {
                var value = values[property];
                var field = this.$el.find('.review-section span.'+property);
                if(field.hasClass('boolean')) {
                    if(value) {
                        value = 'Yes';
                    } else {
                        value = 'No';
                    }
                }
                field.text(value);
            }
        }

        this.showStep(3);
        return false;
    },

    edit_step : function(event) {
        this.showStep($(event.currentTarget).attr('data-target'));
        return false;
    },

    submit : function(event) {
        this.$el.find('form').submit();
        this.showStep(4);
        return false;
    }
});