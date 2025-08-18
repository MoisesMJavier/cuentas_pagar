function inputTomSelect(idElemento, datos = null, parametrosElemento = null) {
    
    if($(`#${idElemento}`)[0].tomselect){
        $(`#${idElemento}`)[0].tomselect.clearOptions();
    }
    if (datos) {        
        $(`#${idElemento}`).hasClass('tomselected')
            ? $(`#${idElemento}`)[0].tomselect.destroy()
            : null;
        new TomSelect(`#${idElemento}`,{        
            maxItems: 1,
            maxOptions: datos ? datos.length : null,
            valueField: parametrosElemento ? parametrosElemento.valor : null,
            labelField: parametrosElemento ? parametrosElemento.texto : null,
            searchField: parametrosElemento ? parametrosElemento.texto : null,
            options: datos ? datos : null,
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: parametrosElemento.opcDataSelect ? 
                {
                    option: function (data, escape) {

                        let atributosDinamicos = '';
                        
                        if (data.opcionesData) {
                            atributosDinamicos = Object.keys(data.opcionesData)
                                .map(key => `data-${key}="${escape(data.opcionesData[key])}"`)
                                .join(' ');
                        }
                        return `<div value="${escape(data.value)}" ${atributosDinamicos}>${data.label}</div>`;
                    },
                    item: function (data, escape) {
                        let atributosDinamicos = '';
                        
                        if (data.opcionesData) {
                            atributosDinamicos = Object.keys(data.opcionesData)
                                .map(key => `data-${key}="${escape(data.opcionesData[key])}"`)
                                .join(' ');
                        }
                        return `<div value="${escape(data.value)}" ${atributosDinamicos}>${data.label}</div>`;
                    }
                } : undefined
                
        });
    }else{
        $(`#${idElemento}`).hasClass('tomselected')
            ? $(`#${idElemento}`)[0].tomselect.destroy()
            : null;
        new TomSelect(`#${idElemento}`,{        
            maxItems: 1,
            maxOptions: datos ? datos.length : null,
            valueField: parametrosElemento ? parametrosElemento.valor : null,
            labelField: parametrosElemento ? parametrosElemento.texto : null,
            searchField: parametrosElemento ? parametrosElemento.texto : null,
            options: datos ? datos : null,
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    }
}