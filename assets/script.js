const body = document.body;
const formAdocao = document.getElementById('form-adocao');
const tipoAnimalSelect = document.getElementById('tipo-animal');
const nomeAnimalInput = document.getElementById('nome-animal');
const idadeAnimalInput = document.getElementById('idade-animal');
const checkboxesCor = document.querySelectorAll('input[name="cor-animal"]');
const btnAdotar = document.getElementById('btn-adotar');
const btnResetForm = document.getElementById('btn-reset-form');
const mensagemSucesso = document.getElementById('mensagem-sucesso');
const animalNomeFinal = document.getElementById('animal-nome-final');

const coresAnimais = {
    'preto': '#343a40',      
    'branco': '#f8f9fa',   
    'marrom': '#795548',    
    'caramelo': '#d2b48c', 
    'cinza': '#6c757d',      
    'default': '#e6f2ff'   
};



function mudarCorFundoPorCorAnimal() {
    let corSelecionada = null;
    checkboxesCor.forEach(checkbox => {
        if (checkbox.checked) {
            corSelecionada = checkbox.value;
           
        }
    });

    if (corSelecionada && coresAnimais[corSelecionada]) {
        body.style.backgroundColor = coresAnimais[corSelecionada];
        
        body.style.color = (corSelecionada === 'preto' || corSelecionada === 'cinza' || corSelecionada === 'marrom') ? '#f8f9fa' : '#333';
    } else {
        body.style.backgroundColor = coresAnimais['default'];
        body.style.color = '#333';
    }
}


function exibirMensagemSucesso(nome) {
    animalNomeFinal.textContent = nome || 'o animal escolhido';
    mensagemSucesso.style.display = 'block';
    setTimeout(() => {
        mensagemSucesso.style.display = 'none';
    }, 5000);
}


document.querySelector('.cor-animal-grupo').addEventListener('change', mudarCorFundoPorCorAnimal);


formAdocao.addEventListener('submit', (event) => {
    event.preventDefault(); 

    
    const tipo = tipoAnimalSelect.value;
    const nome = nomeAnimalInput.value.trim();
    const idade = idadeAnimalInput.value;
    const porte = document.querySelector('input[name="porte"]:checked')?.value;

    let coresSelecionadas = [];
    checkboxesCor.forEach(checkbox => {
        if (checkbox.checked) {
            coresSelecionadas.push(checkbox.value);
        }
    });

    const observacoes = document.getElementById('observacoes').value.trim();

    
    if (!tipo || !nome || !porte || coresSelecionadas.length === 0) {
        alert('Por favor, preencha todos os campos obrigatórios (Tipo, Nome, Porte e Cor do Animal)!');
        return;
    }

    console.log('--- Dados para Adoção ---');
    console.log(`Tipo: ${tipo}`);
    console.log(`Nome Sugerido: ${nome}`);
    console.log(`Idade: ${idade} anos`);
    console.log(`Porte: ${porte}`);
    console.log(`Cores: ${coresSelecionadas.join(', ')}`);
    console.log(`Observações: ${observacoes || 'N/A'}`);
    console.log('-------------------------');

    exibirMensagemSucesso(nome);
   
  
});


btnResetForm.addEventListener('click', () => {
    formAdocao.reset(); 
    body.style.backgroundColor = coresAnimais['default'];
    body.style.color = '#333'; 
    mensagemSucesso.style.display = 'none'; 
    nomeAnimalInput.focus(); 
});


nomeAnimalInput.addEventListener('input', (event) => {
    const nomeDigitado = event.target.value.trim();
    if (nomeDigitado.length > 0) {
        nomeAnimalInput.style.backgroundColor = '#e0ffe0'; 
    } else {
        nomeAnimalInput.style.backgroundColor = '#ffffff'; 
    }
});


document.addEventListener('DOMContentLoaded', () => {
    mudarCorFundoPorCorAnimal(); 
});


const porteAnimalGrupo = document.querySelector('section.campo-grupo:nth-child(4)'); 


function ajustarCamposPorTipoAnimal(tipo) {
    const nomeAnimalInput = document.getElementById('nome-animal');
    
    
    if (tipo === 'cachorro') {
        nomeAnimalInput.placeholder = "Ex: Rex, Luna (Nomes típicos de cachorro)";
        porteAnimalGrupo.style.display = 'block'; 
    } else if (tipo === 'gato') {
        nomeAnimalInput.placeholder = "Ex: Mia, Felix (Nomes típicos de gato)";
        porteAnimalGrupo.style.display = 'block'; 
    } else if (tipo === 'passaro') {
        nomeAnimalInput.placeholder = "Ex: Chico, Jade (Nomes típicos de pássaro)";
        porteAnimalGrupo.style.display = 'none'; 
    } else {
        nomeAnimalInput.placeholder = "Ex: Fido, Mia, Thor";
        porteAnimalGrupo.style.display = 'block';
    }

    if (tipo === 'passaro' || tipo === 'outro') {
         document.querySelectorAll('input[name="porte"]').forEach(radio => {
            radio.checked = false;
        });
    }
}

tipoAnimalSelect.addEventListener('change', (event) => {
  
    ajustarCamposPorTipoAnimal(event.target.value); 
});

document.querySelector('.cor-animal-grupo').addEventListener('change', mudarCorFundoPorCorAnimal);