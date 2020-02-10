/**
 * mb Gutemberg block
 *  Copyright (c) 2001-2018. Matteo Bicocchi (Pupunzi)
 */
var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    ServerSideRender = wp.components.ServerSideRender,
    TextControl = wp.components.TextControl,
    TextareaControl = wp.components.TextareaControl,
    InspectorControls = wp.editor.InspectorControls;

/** Set the icon for your block */
var mb_icon = el ("img", {
    src: "/wp-content/plugins/mb.gutemberg-block/images/YTPL.svg",
    width: "50px",
    height: "50px"
});

registerBlockType( 'mbideas/mb-simple-block', {
    title: 'mb Simple Block',
    icon: "carrot",
    category: 'mb.ideas',

    attributes: {

        'mb_title': {
            type: 'string',
            default: "mb Editor content block"
        },
        'mb_text': {
            type: 'string',
            default: "Write here some text"
        },

        'mb_url': {
            type: 'string',
            default: "https://pupunzi.com"
        },
    },


    edit: (props) => {

        if(props.isSelected){
            //console.debug(props.attributes);
        };

        return [
            /**
             * Server side render
             */
            el("div", {
                    className: "mb-editor-container",
                    style: {textAlign: "center"}
                },
                el( ServerSideRender, {
                    block: 'mbideas/mb-simple-block',
                    attributes: props.attributes
                } )
            ),

            /**
             * Inspector
             */
            el( InspectorControls,
                {}, [
                    el( "hr", {
                        style: {marginTop:20}
                    }),

                    el( TextControl, {
                        label: 'Title',
                        value: props.attributes.mb_title,
                        onChange: ( value ) => {
                            props.setAttributes( { mb_title: value } );
                        }
                    } ),

                    el( TextareaControl, {
                        style: {height:250},
                        label: 'Content',
                        value: props.attributes.mb_text,
                        onChange: ( value ) => {
                            props.setAttributes( { mb_text: value } );
                            console.debug(props.attributes)
                        }
                    }, props.attributes.mb_text ),

                    el( TextControl, {
                        label: 'Url',
                        value: props.attributes.mb_url,
                        onChange: ( value ) => {
                            props.setAttributes( { mb_url: value } );
                        }
                    } ),
                ]
            )
        ]
    },

    save: () => {
        /** this is resolved server side */
        return null
    }
} );