let current_action=[];
let list_html=[];
let load_ajax=[];
let load_init_blog=[];
let list_ajax_process=[];
let list_ajax_save_process=[];
let list_content_of_block=[];
$=jQuery;
$(`.woo-booking-block-edit-content`).find('.btn-config-blog').live('click',function (e) {
    let $block_edit_content=$(this).closest('.woo-booking-block-edit-content');
    let currentClientId=$block_edit_content.attr('data-clientid');
    let type=$block_edit_content.data('type');
    current_action[type] = "block.config";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: root_url + api_task_frontend,
        data: {
            type: type,
            open_source_client_id: currentClientId,
            task: current_action[type]
        },
        beforeSend: function () {
            // setting a timeout
            //
        },
        error: function (xhr) { // if error occured

        },
        complete: function () {

        },
        success: function (response) {
            response = JSON.parse(response);
            if (response.result === "success") {
                loadBlockScripts(response);
                $block_edit_content.find('.block-content').html(response.data);
                $block_edit_content.find('.controllers-save-cancel').show();
                $block_edit_content.find('.btn-config-blog').hide();
            }
            loadBlockScripts(response);
        }
    });
});
$(`.woo-booking-block-edit-content`).find('.btn-preview-block').live('click',function (e) {
    let $block_edit_content=$(this).closest('.woo-booking-block-edit-content');
    let currentClientId=$block_edit_content.attr('data-clientid');
    let type=$block_edit_content.data('type');
    current_action[type] = "block.ajax_get_preview_blog";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: root_url + api_task_frontend,
        data: {
            type: type,
            open_source_client_id: currentClientId,
            task: "block.ajax_get_preview_blog"
        },
        beforeSend: function () {
            // setting a timeout
            //
        },
        error: function (xhr) { // if error occured

        },
        complete: function () {

        },
        success: function (response) {
            response = JSON.parse(response);
            list_content_of_block[currentClientId]=response.data;
            if (response.result === "success") {
                loadBlockScripts(response);
                $block_edit_content.find('.block-content').html(response.data);
            }
            $block_edit_content.find('.controllers-save-cancel').hide();
            $block_edit_content.find('.btn-config-blog').show();
            loadBlockScripts(response);
        }
    });
});
$(`div.woo-booking-block-edit-content`).find('.btn-cancel-block').live('click',function (e) {
    let $block_edit_content=$(this).closest('.woo-booking-block-edit-content');
    let currentClientId=$block_edit_content.attr('data-clientid');
    let type=$block_edit_content.data('type');
    current_action[type] = "block.preview";
    $.ajax({
        type: "POST",
        dataType: "json",
        url: root_url + api_task_frontend,
        data: {
            type: type,
            open_source_client_id: currentClientId,
            task: current_action[type]
        },
        beforeSend: function () {
            // setting a timeout
            //
        },
        error: function (xhr) { // if error occured

        },
        complete: function () {

        },
        success: function (response) {
            response = JSON.parse(response);
            list_content_of_block[currentClientId]=response.data;
            loadBlockScripts(response);
            if (response.result === "success") {

                $block_edit_content.find('.block-content').html(response.data);
            }
            $block_edit_content.find('.controllers-save-cancel').hide();
            $block_edit_content.find('.btn-config-blog').show();
        }
    });
});
$(`div.woo-booking-block-edit-content`).find('.btn-save-block').live('click',function (e) {
    let $block_edit_content=$(this).closest('.woo-booking-block-edit-content');
    let currentClientId=$block_edit_content.attr('data-clientid');
    let type=$block_edit_content.data('type');
    current_action[type] = "block.preview";
    if($block_edit_content.find('form').length===0){
        $.alert({
            title: 'Error ',
            content: 'Cannot found Form !, you need add tag form in layout config',
        });
        return;
    }
    $block_edit_content.find('form').serializeObject().done(function(data){
        data['type']=type;
        data['open_source_client_id']=currentClientId;
        data['task']=current_action[type];
        $.ajax({
            type: "POST",
            dataType: "json",
            url: root_url + api_task_frontend,
            data: data,
            beforeSend: function () {
                // setting a timeout
                //
            },
            error: function (xhr) { // if error occured

            },
            complete: function () {

            },
            success: function (response) {
                response = JSON.parse(response);
                list_content_of_block[currentClientId]=response.data;
                if (response.result === "success") {
                    loadBlockScripts(response);
                    $block_edit_content.find('.block-content').html(response.data);
                }
                $block_edit_content.find('.controllers-save-cancel').hide();
                $block_edit_content.find('.btn-config-blog').show();
            }
        });
    });
});
render_wrapper_block=function(clientId,wp,key,key_html,props,content){
    let aInterval = setInterval(function() {
        let $block=$(`div.${clientId}`);
        if($block.length>0){
            $block.addClass('has-config');

            loadBlockScripts(list_html[key_html]);
            clearInterval(aInterval);
        }
    },1000);
    function updateColor() {
        console.log("175 props",props);
    }
    return wp.element.createElement( wp.editor.RichText.Content, {
        tagName: 'div',
        className: 'woo-booking-block-edit',
        value: `<div data-type="${key}" class="${clientId} woo-booking-block-edit-content"  data-clientId="${clientId}"   >
                <div class="row">
                    <div class="col-md-12">
                    <div class="block-content">${content}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="controllers-preview-config">
                            <button class="btn btn-primary btn-config-blog">Config</button>
                        </div>
                        <div class="controllers-save-cancel" style="display: none">
                            <button class="btn btn-primary btn-save-block">Save</button>
                            <button class="btn btn-primary btn-cancel-block">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>`


    });


};
console.log("list_view",list_view);
jQuery.each(list_view,function (key, item) {
    wp.blocks.registerBlockType(`woobooking/${key}`, {
        title: item.title,
        icon: 'megaphone',
        category: 'woobooking-block',
        attributes: {
            open_source_client_id:{type: 'string'}
        },
        edit: function( props ) {

            let clientId=props.attributes.open_source_client_id;

            if(typeof clientId==="undefined"){
                clientId= props.clientId;
            }
            let key_html=`${key}-${clientId}`;
            console.log('list_html[key_html]',list_html[key_html]);
            if(typeof list_html[key_html] !=="undefined"){
                let content=list_html[key_html].data;
                if(typeof list_content_of_block[clientId]!=="undefined"){
                    content=list_content_of_block[clientId];
                }
                props.setAttributes({open_source_client_id:clientId});
                current_action[key]=typeof  current_action[key]!=="undefined"?current_action[key]:"block.preview";
                return  render_wrapper_block(clientId,wp,key,key_html,props,content);
            }else {
                current_action[key] = typeof current_action[key] !== "undefined" ? current_action[key] : "block.preview";
                props.setAttributes({open_source_client_id: clientId});
                if (typeof load_ajax[key_html] === "undefined") {
                    load_ajax[key] = 1;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: root_url + api_task_frontend,
                        data: {
                            type: key,
                            open_source_client_id: clientId,
                            task: current_action[key]
                        },
                        beforeSend: function () {
                            // setting a timeout
                            //
                        },
                        error: function (xhr) { // if error occured

                        },
                        complete: function () {

                        },
                        success: function (response) {
                            response = JSON.parse(response);
                            console.log("response", response);
                            if (response.result === "success") {

                                list_html[key_html] = response;
                                list_content_of_block[clientId] = response.data;
                                $(`div.${clientId}`).find('.block-content').html(response.data);
                                loadBlockScripts(response);
                                if (current_action[key] === "block.preview") {
                                    $(`div.${clientId}`).find('.controllers-save-cancel').hide();
                                    $(`div.${clientId}`).find('.btn-config-blog').show();
                                } else {
                                    $(`div.${clientId}`).find('.controllers-save-cancel').show();
                                    $(`div.${clientId}`).find('.btn-config-blog').hide();
                                }
                            }
                        }
                    });
                }
                return render_wrapper_block(clientId,wp,key,key_html,props,'');
            }


        },
        save: function( props ) {

            return null;
        }
    });
});
jQuery.getBlockMultiScripts = function (arr, path) {
    var _arr = $.map(arr, function (scr) {
        return $.getScript(scr);
    });
    _arr.push($.Deferred(function (deferred) {
        $(deferred.resolve);
    }));

    return $.when.apply($, _arr);
};
jQuery.getBlockMultiLess = function (arr, path) {
    var _arr_less = $.map(arr, function (scr) {
        let  current_less = document.createElement('link');
        current_less.setAttribute('rel','stylesheet/less');
        current_less.setAttribute('type','text/css');
        current_less.setAttribute('href',scr);
        document.head.appendChild(current_less);

        //jQuery('head').append(`<link rel="stylesheet/less" href="${scr}" type="text/css" />`);
    });
    _arr_less.push($.Deferred(function (deferred) {
        $(deferred.resolve);
    }));
    return $.when.apply($, _arr_less);
};
list_js_installed=[];
list_less_install=[];
function loadBlockScripts(response) {
    $ = jQuery;
    $('link[rel="stylesheet/less"]').remove();
    if(typeof response.styleSheets==="undefined"){
        console.trace();
    }
    var styleSheets = response.styleSheets;

    $.each(styleSheets, function (src, value) {
        if (src.indexOf('http') >= 0) {
            $('head').append(`<link rel="stylesheet" href="${src}" type="text/css" />`);
        } else {
            $('head').append(`<link rel="stylesheet" href="${root_url_plugin}${src}" type="text/css" />`);
        }
    });
    less_arr = [];
    var lessStyleSheets = response.lessStyleSheets;
    $.each(lessStyleSheets, function (src, value){
        if( src.indexOf('http') >= 0){
            less_arr.push(src);
        }else{
            less_arr.push(root_url_plugin+src);
        }
    });

    $.getBlockMultiLess(less_arr, root_url_plugin).done(function () {
        script_arr = [];
        var scripts = response.scripts;
        $.each(scripts, function (src, value) {
            if( src.indexOf('http') >= 0){
                script_arr.push(src);
            }else if(src.indexOf('less.min.js') === -1) {
                script_arr.push(root_url_plugin+src);
            }
        });

        $.getBlockMultiScripts(script_arr, root_url_plugin).done(function () {
            var script = response.script;
            $('head').append(`<script type="text/javascript">${script['text/javascript']}</script>`);
        });
    });
}