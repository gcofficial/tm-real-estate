( function( $ ) {
 $( document ).ready( function( ) {

    var filesData = [],
        filesCount = 0,
        formData, i;

        'use strict';
        $( '#galery' ).fileupload({
            filesCount: 0,
            dataType: 'json',
            autoUpload: false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 999000,
            disableImageResize: /Android(?!.*Chrome)|Opera/.test( window.navigator.userAgent ),
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on( 'fileuploadadd', function( e, data ) {
            data.context = $( '<div class="col-xs-6 col-sm-2">' ).appendTo( '#files' );
            $.each( data.files, function( index, file ) {
                var node = $( '<p class="text-center"/>' )
                        .append( $( '<span/>' ).text( file.name ) )
                        .append( $( '<input type="text" name="property[meta][gallery][title][' + filesCount + ']"/><span class="close" data-index="' + filesCount + '" ></span>' ) );
                filesData[ filesCount ] = file;
                filesCount++;

                if ( ! index ) {
                    node.append( '<br>' );
                }
                node.appendTo( data.context );
            });
        }).on( 'fileuploadprocessalways', function( e, data ) {
            var index = data.index,
                    file = data.files[ index ],
                    node = $( data.context.children()[ index ] );
            if ( file.preview ) {
                node
                        .prepend( '<br>' )
                        .prepend( file.preview );
            }
            if ( file.error ) {
                node
                        .append( '<br>' )
                        .append( $( '<span class="text-danger"/>' ).text( file.error ) );
            }
            if ( index + 1 === data.files.length ) {
                data.context.find( 'button' )
                        .text( 'Upload' )
                        .prop( 'disabled', ! data.files.error );
            }
        });
    $( document ).on( 'click', 'span.close', function( ) {
        filesData.splice( $( this ).data( 'index' ), 1 );
        $( this ).parent().parent().remove();
    });
    $( '#property_submit_format' ).on( 'submit', function( event ) {
        formData = new FormData( this );
        if ( filesCount ) {
            for ( i = 0; i < filesCount; i++ ) {
                formData.append( 'gallery[' + i + ']', filesData[ i ] );
            }
        }
        event.preventDefault( );
        $.ajax({
            url: window.formUrl.url + '?action=submit_form',
            processData: false,
            contentType: false,
            method: 'POST',
            dataType: 'json',
            data: formData,
            success: function( responce ) {
                $( '.tm-form-preloader' ).css( 'display', 'none' );
                if ( responce.success ) {
                    $( '#property_submit_format' ).replaceWith( '<div>' + responce.data.messages['success-message'] + '</div>' );
                } else {
                    $( '#property_submit_format' ).replaceWith( '<div>' + responce.data.messages['failed-message'] + '</div>' );
                }
            },
            beforeSend: function() {
                $( '.tm-form-preloader' ).css( 'display', 'block' );
            }
        });
    });
});
}( jQuery ) );
