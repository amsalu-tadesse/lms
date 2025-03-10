/*
 *  Project: jQuery Light Weight MultiSelect
 *  Version: 1.1.0
 *  Date: 7/11/2013
 *  Requires: jQuery 1.7+
 *  Description: A light weight plugin that transforms a multi select drop menu into two panels for easy selections.
 *  Demo: http://bennylin.net/jquery-plugin-light-weight-multiselect/
 *  Author: Benny Lin - http://bennylin.net
 *  Designer: Monique Aviles
 *  License: MIT License
 *  jQuery Boilerplate: http://jqueryboilerplate.com/
 */

;
(function($, window, document, undefined) {
    var pluginName = "lwMultiSelect";
    var defaults = {
        addAllText: "Select All",
        removeAllText: "Remove All",
        selectedLabel: "Values accepted",
        maxSelect: 0, //0 = no restrictions
        maxText: '',
        onChange: ''
    };
    // The actual plugin constructor
    function Plugin(element, options) {
        this.$element = $(element);
        if (options) {
            options.maxSelect = parseInt(options.maxSelect) || defaults.maxSelect;
        }
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.totalItem = 0; //cache total count
        this.$mainContainer = "";
        this.$leftDiv = "";
        this.$rightDiv = "";
        this.$leftHead = "";
        this.$rightHead = "";
        this.$availList = "";
        this.$selectedList = "";
        this.$counter = "";
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var tmphtml = "";
            this.$element.hide();
            this.$mainContainer = $('<div class="lwms-main lwms-cf"></div>').insertAfter(this.$element);
            this.$mainContainer.append("<div class='form-group'><input type='search' id='user_search' placeholder='Search User' style='font-family: Arial;width: 45%' class='form-control'/></div>");
            this.$leftDiv = $('<div class="lwms-left"></div>').appendTo(this.$mainContainer);
            this.$rightDiv = $('<div class="lwms-right"></div>').appendTo(this.$mainContainer);
            if (this.options.maxSelect != 0) {
                tmphtml = '<div class="lwms-filterhead"><div class="lwms-maxtext">' + this.options.maxText + '</div></div>';
            } else {
                tmphtml = '<div class="lwms-filterhead"><a href="#" class="lwms-addall">' + '</a>&nbsp; | &nbsp;<a href="#" class="lwms-removeall">' + this.options.removeAllText + '</a></div>';
            }
            this.$leftHead = $(tmphtml).appendTo(this.$leftDiv);
            this.$rightHead = $('<div class="lwms-filterhead"><span class="lwms-filcount"></span> ' + this.options.selectedLabel + '</div>').appendTo(this.$rightDiv);
            this.$availList = $('<ul class="lwms-list lwms-available"></ul>').appendTo(this.$leftDiv);
            this.$selectedList = $('<ul class="lwms-list"></ul>').appendTo(this.$rightDiv);
            this.$counter = this.$mainContainer.find('.lwms-filcount');

            this.updateList();
            this.initEvents();
        },

        //return if current selected is not more than max select
        isBelowMax: function() {
            return (this.$selectedList.children().length < this.options.maxSelect);
        },

        /* event: clicking on left container li */
        selectItem: function(that) {
            if (this.options.maxSelect == 0 || this.isBelowMax()) { //allow selection if there's no max or selection is below max
                var $self = $(that);
                $self.clone().appendTo(this.$selectedList); //clone the element and append to selected, this is required due to search visibility
                $self.addClass('lwms-selected choosen'); //lwms-selected is to preserved visibility state of search filters
                $value = $self.data('value');
                $index = users_temp.temp_id.indexOf($value.toString());
                users_temp.temp_id.splice($index, 1);
                users_temp.temp_name.splice($index, 1);
                this.$element.find('option[value="' + $value + '"]').attr('selected', 'selected'); //mark selected on the source, used attr vs prop because clone doesn't carry over selected attr
                this.updateCount(); //refresh counts
                this.triggerChange(); //trigger change callback    
            }
        },

        /* event: clicking on right container li */
        removeItem: function(that) {
            var $self = $(that);
            index = users.user_id.indexOf($self.data('value').toString());
            users_temp.temp_id.push(users.user_id[index]);
            users_temp.temp_name.push(users.users_name[index]);
            this.$availList.find('li[data-value="' + $self.data('value') + '"]').removeClass('lwms-selected choosen'); //remove lwms-selected from available list
            this.$element.find('option[value="' + $self.data('value') + '"]').removeAttr('selected'); //remove selected on the source
            $self.remove(); //remove the current element from selected 
            this.updateCount(); //refresh counts
            this.triggerChange(); //trigger change callback
        },

        /* event: selecting all visible */
        selectAll: function() {
            if (this.options.maxSelect == 0) { //only allow this if no max specified
                var tmpArrId, $tempAddList = this.$availList.find('li:visible'); //cache list of visible items only (search filters and items not already selected)

                this.$selectedList.append($tempAddList.clone()); //clone the list and append to selected

                tmpArrId = $tempAddList.map(function() {
                    return $(this).data('value').toString(); //convert this to string, data values are typed 
                }).get(); //get an array of selected values        

                //update the source select
                this.$element.find('option').filter(function(index) {
                    return ($.inArray(this.value, tmpArrId) > -1); //inArray checks with ===, we need tmpArrId to be a array of strings
                }).attr('selected', 'selected'); //used attr vs prop because clone doesn't carry over selected attr

                $tempAddList.addClass('lwms-selected choosen'); //mark them with lwms-selected
                this.updateCount();
                this.triggerChange();
            }
        },

        /* event: remove all */
        removeAll: function() {
            this.$availList.find('li').removeClass('lwms-selected choosen'); //remove all lwms-selected from available
            this.$selectedList.empty(); //clear selected list
            count = users.user_id.length;
            users_temp.temp_name = [];
            users_temp.temp_id = [];
            for (i = 0; i < count; i++) {
                users_temp.temp_name.push(users.users_name[i]);
                users_temp.temp_id.push(users.user_id[i]);
            }
            this.$element.children().removeAttr('selected'); //remove all selected from source, used attr vs prop because clone doesn't carry over selected attr

            this.updateCount();
            this.triggerChange();
        },

        //initial all event handlers
        initEvents: function() {
            var that = this;
            //event delegations

            /* clicking items on left container */
            this.$availList.off('click.lwmultiselect', 'li');
            this.$availList.on('click.lwmultiselect', 'li', function(e) {
                e.preventDefault();
                that.selectItem(this);
            });

            /* remove clicks */
            this.$selectedList.off('click.lwmultiselect', 'li');
            this.$selectedList.on('click.lwmultiselect', 'li', function(e) {
                e.preventDefault();
                that.removeItem(this);
            });

            //select all
            this.$mainContainer.off('click.lwmultiselect', '.lwms-addall');
            this.$mainContainer.on('click.lwmultiselect', '.lwms-addall', function(e) {
                e.preventDefault();
                that.selectAll();
            });

            //remove all
            this.$mainContainer.off('click.lwmultiselect', '.lwms-removeall');
            this.$mainContainer.on('click.lwmultiselect', '.lwms-removeall', function(e) {
                e.preventDefault();
                that.removeAll();
            });
        },

        //refresh the containers with the elements from the original source
        updateList: function() {
            var that = this,
                tmpSelectHtml = '',
                tmpAvailHtml = '',
                $tmpThis,
                tmpVal,
                tmpText,
                users_name = [],
                users_id = [],
                permission_name = [],
                permission_id = [],
                selectClass = '';

            this.$selectedList.empty(); //clear available and selected list
            this.$availList.empty();
            this.totalItem = this.$element.children().length; //cache the total per update

            //loop through the source and rebuild the list
            this.$element.children().each(function() {
                selectClass = '';
                $tmpThis = $(this);
                tmpVal = $tmpThis.prop('value');
                tmpText = $tmpThis.text();
                users.user_id.push(tmpVal);
                users.users_name.push(tmpText);
                //if option is preselected then append to selected and add lwms-selected to the available side        
                if ($tmpThis.is(':selected')) {
                    tmpSelectHtml += '<li class="lwms-selectli" data-value="' + tmpVal + '">' + tmpText + '</li>';
                    selectClass = ' lwms-selected';
                } else {
                    users_temp.temp_name.push(tmpText);
                    users_temp.temp_id.push(tmpVal);
                }
                tmpAvailHtml += '<li class="lwms-selectli' + selectClass + '" data-value="' + tmpVal + '">' + tmpText + '</li>';
            });

            this.$selectedList.html(tmpSelectHtml);
            this.$availList.html(tmpAvailHtml);
            this.updateCount();
        },

        /* call back function if defined */
        triggerChange: function() {
            if (this.options.onChange != '') {
                this.options.onChange();
            }
        },

        /* refresh count */
        updateCount: function() {
            this.$counter.text(this.$selectedList.children().length + '/' + ((this.options.maxSelect == 0) ? this.totalItem : this.options.maxSelect));
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };
})(jQuery, window, document);