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
    xhr.open( 'POST', '_send.php', true )
    xhr.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' )
    xhr.send( 'content=' + encodeURI( document.getElementById( 'content' ).value ) )
    return false
}

function update() {
    let xhr = new XMLHttpRequest()
    xhr.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {
            let history = document.getElementById( 'history' )
            history.innerHTML = this.responseText
            history.scrollTop = history.scrollHeight
        }
    }
    xhr.open( 'POST', '_history.php', true )
    xhr.send()
}

setInterval( update, 5000 )