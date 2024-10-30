const smart = {

    apiCall: function(url, form, callbackSuccess, callbackError){

        console.log(url)
    
        fetch(`http://localhost:8000${url}`, {method: 'POST', body: form})
        .then(res => res.json())
        .then((data) => {callbackSuccess(data)})
        .catch(() => {callbackError('Se ha producido un error de red.')})
    
    },

    fillSelect: function(apiUrl, selectElement, clubID = null){

        const form = new FormData()
        form.append('page', 1)
    
        this.apiCall(apiUrl, form, (data) => {

            let resultado = '<option value="">--Selecciona una opción--</option>'
            for(let element of JSON.parse(data)){
                if(clubID && (!element.club || element.club.id != clubID)) continue
                resultado += `<option value="${element.id}">${element.name}</option>`
            }
            
            selectElement.innerHTML = resultado

        }, this.errorConsole)
    
    },

    errorConsole: function(message){
        console.log(message)
    },
    
}