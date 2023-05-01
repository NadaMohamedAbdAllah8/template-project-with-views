jQuery( document ).ready( function () {
    var inputs = document.getElementsByClassName( "read-only-input" );
    for ( var i = 0; i < inputs.length; i++ ) {
        inputs.item( i ).readOnly = true;
    }
} );
