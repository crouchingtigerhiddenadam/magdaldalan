function receive() {
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if ( request.readyState == 4 && request.status == 200 ) {
            let response, section

            // update contacts
            response = request.responseText.match(/<section\s+id="contacts">[\S\s]*?<\/section>/gi)[0]
            section = document.getElementById( 'contacts' )
            if ( section.outerHTML != response ) section.outerHTML = response

            // update messages
            response = request.responseText.match(/<section\s+id="messages">[\S\s]*?<\/section>/gi)[0]
            section = document.getElementById( 'messages' )
            if ( section.outerHTML !== response ) {
                section.outerHTML = response
                scroll()
            }
        }
    }
    request.open( 'POST', '_received.php' + location.search, true )
    request.send()
}

function scroll() {
    let section = document.getElementById( 'messages' )
    section.scrollTop = section.scrollHeight
}

function send( e ) {
    e.preventDefault()
    let request = new XMLHttpRequest()
    request.onreadystatechange = function() {
        if ( request.readyState == 4 && request.status == 200 ) {
            let control, section

            // update send section
            section = document.getElementById( 'send' )
            section.innerHTML = request.responseText

            // focus content textbox
            control = document.getElementById( 'content' )
            control.focus()
            receive()
        }
    }
    request.open( 'POST', '_send.php' + location.search, true )
    request.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
    request.send( 'content=' + encodeURI( document.getElementById( 'content' ).value ) )
    return false
}

setInterval( receive, 5000 )
scroll()