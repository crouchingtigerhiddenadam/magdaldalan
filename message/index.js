function send( e ) {
    
    e.preventDefault()

    let send = new XMLHttpRequest()
    send.onreadystatechange = function() {
        if ( send.readyState == 4 && send.status == 200 ) {
            document.getElementById( 'send' ).innerHTML = send.responseText
            document.getElementById( 'content' ).focus()
            update()
        }
    }
    send.open( 'POST', '_send.php' + location.search, true )
    send.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
    send.send( 'content=' + encodeURI( document.getElementById( 'content' ).value ) )

    return false
}

function update() {

    let contacts = new XMLHttpRequest()
    contacts.onreadystatechange = function() {
        if ( contacts.readyState == 4 && contacts.status == 200 ) {
            document.getElementById( 'contacts' ).innerHTML = contacts.responseText
        }
    }
    contacts.open( 'POST', '_contacts.php' + location.search, true )
    contacts.send()

    let history = new XMLHttpRequest()
    history.onreadystatechange = function() {
        if ( history.readyState == 4 && history.status == 200 ) {
            let section = document.getElementById( 'history' )
            section.innerHTML = history.responseText
            section.scrollTop = section.scrollHeight
        }
    }
    history.open( 'POST', '_history.php' + location.search, true )
    history.send()
}

let history = document.getElementById( 'history' )
history.scrollTop = history.scrollHeight
setInterval( update, 5000 )