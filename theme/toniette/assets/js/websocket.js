ws = new WebSocket('ws://localhost:8080');

ws.onopen = ev => {
    console.log(ev)
}

ws.onmessage = ev => {
    console.log(ev.data)
}

ws.onclose = ev => {
    console.log(ev.data)
}

ws.onerror = ev => {
    console.log(ev.data)
}