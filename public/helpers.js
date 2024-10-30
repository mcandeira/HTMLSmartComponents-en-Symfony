const smart = {

    apiCall: function(url, form, callbackError, callbackSuccess){

        const URL = url.startsWith('http') ? url : `http://localhost:8000${url}`
    
        fetch(URL, {method: 'POST', body: form})
        .then(res => res.json())
        .then((data) => {callbackSuccess(data)})
        .catch(() => {callbackError('Se ha producido un error de red.')})
    
    },

    fillSelect: function(apiUrl, selectElement, clubID = null){

        const form = new FormData()
        form.append('page', 1)
    
        this.apiCall(apiUrl, form, this.errorConsole, (data) => {
            
            let resultado = '<option value="">--Selecciona una opción--</option>'
            for(let element of data.resultado){
                if(clubID && (!element.club || element.clubId != clubID)) continue
                resultado += `<option value="${element.id}">${element.name}</option>`
            }
            
            selectElement.innerHTML = resultado
        })
    
    },

    errorConsole: function(message){
        console.log(message)
    },
    
}