const smart = {

/* =====================================================================
    FUNCIONES DE RED
   ===================================================================== */
    
    http: 'http://localhost:8000',

    apiCall: function(url, form, callbackError, callbackSuccess){

        const URL = url.startsWith('http') ? url : this.http + url
    
        fetch(URL, {method: 'POST', body: form})
        .then(res => res.json())
        .then((data) => {callbackSuccess(data)})
        .catch(() => {callbackError('Se ha producido un error de red.')})
    
    },

    errorConsole: function(message){
        console.log(message)
    },
    
/* =====================================================================
    FUNCIONES DE FORMULARIO
   ===================================================================== */

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

    generarSalida(datos, elementoSalida){
        
        let outputText = ''
        for(let element of datos){
            outputText += '<li><ul>'
            for(property in element){

                const clave = property[0].toUpperCase() + property.substring(1)

                outputText += property == 'club' && element[property] ?
                    `<li><strong>${clave}</strong>: ${element[property].name ?? 'No'}.</li>` :
                    `<li><strong>${clave}</strong>: ${element[property] ?? 'No'}.</li>`
            }
            outputText += '</li></ul>'
        }

        elementoSalida.innerHTML = outputText
        elementoSalida.style.border = 'solid 1px black'

    },

    crearPaginacion(formulario, contenedorPaginas, seleccionPagina, numeroPaginas){

        contenedorPaginas.innerHTML = ''

        for(let i = 1; i <= numeroPaginas; i++){

            let fila = document.createElement('li')
            fila.classList.add('page-item')

            let boton = document.createElement('button')
            boton.classList.add('page-link')
            boton.textContent = i

            boton.addEventListener('click', () => {
                seleccionPagina.value = i
                formulario.requestSubmit()
                seleccionPagina.value = 1
            })

            fila.appendChild(boton)
            contenedorPaginas.appendChild(fila)
        }

    },

}