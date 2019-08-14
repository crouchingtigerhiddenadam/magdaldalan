function send( e ) {
    e.preventDefault()
    let xhr = new XMLHttpRequest()
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {
            document.getElementById( 'send' ).innerHTML = this.responseText
            document.getElementById( 'content' ).focus()
            update()
        }
    }
    xhr.open( 'POST', '_send.php' + location.search, true )
    xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
    xhr.send( 'content=' + encodeURI( document.getElementById( 'content' ).value ) )
    return false
}

function update() {

    let xhr1 = new XMLHttpRequest()
    xhr1.onreadystatechange = function() {
        if ( xhr1.readyState == 4 && xhr1.status == 200 ) {
            document.getElementById( 'contacts' ).innerHTML = xhr1.responseText
        }
    }
    xhr1.open( 'POST', '_contacts.php' + location.search, true )
    xhr1.send()

    let xhr2 = new XMLHttpRequest()
    xhr2.onreadystatechange = function() {
        if ( xhr2.readyState == 4 && xhr2.status == 200 ) {
            let history = document.getElementById( 'history' )
            history.innerHTML = xhr2.responseText
            history.scrollTop = history.scrollHeight
        }
    }
    xhr2.open( 'POST', '_history.php' + location.search, true )
    xhr2.send()
}

let history = document.getElementById( 'history' )
history.scrollTop = history.scrollHeight
setInterval( update, 5000 )