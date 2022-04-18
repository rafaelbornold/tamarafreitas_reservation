const KEY = {
  live: 'pk_live_51JoWx...',
  test: 'pk_test_51JoWx...',
};

const STRIPE_PUBLIC_KEY = KEY.test;

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

var activePayment = false;
let elements;
let formDatas;
let registers;

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

// eslint-disable-next-line no-undef
const stripe = Stripe(STRIPE_PUBLIC_KEY);

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

const getInfoPlazaPeriod = async (formDatas) => {
  try {
    const INFOPLAZAPERIOD = await fetch('./operations/info_plazas.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ formDatas }),
    }).then((r) => r.json());

    return INFOPLAZAPERIOD;
  } catch (err) {
    console.log(err);
  }
}; // API : busca as informações de Vagas e Periodos disponiveis no Banco de dados

const createRegistration = async (formDatas) => {
  try {
    const REGISTERS = await fetch('./operations/create_registrations.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ formDatas }),
    }).then((r) => r.json());

    registers = REGISTERS;
    return REGISTERS;
  } catch (err) {
    console.log(err);
  }
}; // API : cria e organiza os registros da pessoa a serem cadastrados no Banco de dados

async function IncludeRegistersOnDataBase(registers) {
  try {
    const STATUS = await fetch('./operations/includeDB.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ registers }),
    }).then((r) => r.json());

    return STATUS;
  } catch (err) {
    console.log(err);
  }
} // API : inclue os registros no banco de dados

async function confirmPaymentOnDB(confirmPaymentResult, registers) {
  try {
    const CONFIRMATION = await fetch('./operations/confirmPaymentOnDB.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ confirmPaymentResult, registers }),
    }).then((r) => r.json());

    return CONFIRMATION;
  } catch (err) {
    console.log(err);
  }
} // API : inclue as informacoes/Confirmações de pagamento no Banco de Dados

async function verifyRegisterPayment(registers) {
  try {
    const OUTPUT = await fetch('./operations/verifyPayment.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ registers }),
    }).then((r) => r.json());

    return OUTPUT;
  } catch (err) {
    console.log(err);
  }
} // API : verifica se algum dos procedimentos selecionados já foram pagos pela pessoa(NIF);

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

function collectDataForm() {
  if (document.getElementById('dispManana').checked) {
    document.getElementById('dispManana').value = 1;
  } else {
    document.getElementById('dispManana').value = 0;
  }

  if (document.getElementById('dispTarde').checked) {
    document.getElementById('dispTarde').value = 1;
  } else {
    document.getElementById('dispTarde').value = 0;
  }

  if (document.getElementById('ProcCejas').checked) {
    document.getElementById('ProcCejas').value = 1;
  } else {
    document.getElementById('ProcCejas').value = 0;
  }

  if (document.getElementById('ProcLabios').checked) {
    document.getElementById('ProcLabios').value = 1;
  } else {
    document.getElementById('ProcLabios').value = 0;
  }

  if (document.getElementById('ProcEyeliner').checked) {
    document.getElementById('ProcEyeliner').value = 1;
  } else {
    document.getElementById('ProcEyeliner').value = 0;
  }

  const form = [
    {
      operation: document.getElementById('operation').value.trim(),
      nombre: document.getElementById('nombre').value.trim(),
      apellido: document.getElementById('apellido').value.trim(),
      nif: document.getElementById('nif').value.trim(),
      email: document.getElementById('email').value.trim(),
      telefono: document.getElementById('telefono').value.trim(),
      dispManana: document.getElementById('dispManana').value.trim(),
      dispTarde: document.getElementById('dispTarde').value.trim(),
      procCejas: document.getElementById('ProcCejas').value.trim(),
      procLabios: document.getElementById('ProcLabios').value.trim(),
      procEyeliner: document.getElementById('ProcEyeliner').value.trim(),
      periodoAno: document.getElementById('periodo_ano').value.trim(),
      periodoMesNumero: document.getElementById('periodo_mesNumero').value.trim(),
      periodoMesNombre: document.getElementById('periodo_mesNombre').value.trim(),
      condicionBasica: document.getElementById('condicionBasica').value.trim(),
      condicionEspecifica: document.getElementById('condicionEspecifica').value.trim(),
    },
  ];

  return form;
} // Coleta os dados do formulario de registro da pessoa

async function checkStatus(stripe) {
  const clientSecret = new URLSearchParams(window.location.search).get('payment_intent_client_secret');

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  const ReceiptInfos = await fetch('./operations/stripeRetrive.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ paymentIntent }),
  }).then((r) => r.json());

  switch (paymentIntent.status) {
    case 'succeeded':
      var linkReceipt = `
      <span>
        <a href="${ReceiptInfos.receipt_url}" target="_blank" title="Haga clic para ver el Recibo">VER RECIBO DE NUMERO ${ReceiptInfos.receipt_number}</a>
      </span>
       `;

      var message = `
      <span>${ReceiptInfos.description}</span><br>
      <span>Pronto recibirá su recibo en el correo electrónico registrado (${ReceiptInfos.receipt_email}), pero también puede verlo haciendo clic abajo:</span> <br>
      ${linkReceipt}`;

      showMessage(message, 'status_success');
      break;

    case 'processing':
      showMessage(
        'TU PAGO SE ESTÁ PROCESANDO. ENTRE EN CONTACTO CON EL STUDIO PARA CONFIRMAR EL PAGO',
        'status_warning',
      );
      break;

    case 'requires_payment_method':
      showMessage('Tu pago no se realizó correctamente, inténtalo de nuevo.', 'status_error');
      break;

    default:
      showMessage('Algo salió mal.', 'status_error');
      break;
  }
}
checkStatus(stripe); // verifica o status do pagamento, caso a url contenha um payment-intent

document.querySelector('#register-form').addEventListener('submit', checkoutShow);
async function checkoutShow() {
  clearMessageBox();

  formDatas = collectDataForm();

  createRegistration(formDatas).then((registers) => {
    verifyRegisterPayment(registers).then((verityOutput) => {
      if (verityOutput == 200) {
        getInfoPlazaPeriod(formDatas).then((infoPlazaPeriod) => {
          if (infoPlazaPeriod.plazas >= infoPlazaPeriod.countSelectedProcedures) {
            var form_up = document.getElementById('register-form');
            var form_left = document.getElementById('form_left');
            var form_right = document.getElementById('form_right');
            var form_down = document.getElementById('payment-form');

            //const div = document.querySelector('#divProcedimientos');
            //const checkboxes1 = div.querySelectorAll('input[type=checkbox]');
            //const checkboxLength1 = checkboxes1.length;

            //////////////////////////////////
            //////////////////////////////////
            //////////////////////////////////

            form_left.style.opacity = 0;
            form_left.style.visibility = 'hidden';
            setTimeout(function () {
              form_left.style.display = 'none';
            }, 1000);

            form_right.style.opacity = 0;
            form_right.style.visibility = 'hidden';
            setTimeout(function () {
              form_right.style.display = 'none';
            }, 1000);

            form_down.style.opacity = 1;
            form_down.style.visibility = 'visible';
            setTimeout(function () {
              form_down.style.position = 'static';
              form_up.style.position = 'absolute';
            }, 1000);

            if (!activePayment) {
              initialize(stripe);
              activePayment = true;
            }
          } else {
            alert(
              'Se han agotado las plazas por la cantidad de procedimientos que desea realizar. Seleccione un número menor de procedimientos o intente el proceso nuevamente.',
            );
          }
        });
      } else {
        alert(verityOutput);
      }
    });
  });
} // Esconde o Formulario de registro e mostra ao formulario de pagamento

async function initialize(stripe) {
  // Aqui só entra em caso de já verificado se existe plazas para seguir com o processo.

  createRegistration(formDatas).then((registers) => {
    var registerName = '';
    var procedures = 'Procedimiento(s): ';
    var email_payment = '';
    var reservation_amount = 0;
    var reservationPeriod = '';

    registers.forEach((element, i) => {
      registerName = registers[i].nombre + ' ' + registers[i].apellido;
      procedures += registers[i].procedure + ' ';
      email_payment = registers[i].email;
      reservation_amount += parseInt(registers[i].reservationPrice);
      reservationPeriod = `${registers[i].mesNombre}-${registers[i].ano}`;
    });

    document.getElementById('registerName').textContent = registerName;
    document.getElementById('procedures').textContent = procedures;
    document.getElementById('email_payment').textContent = email_payment;
    document.getElementById('reservation_amount').textContent = 'TOTAL RESERVA: ' + reservation_amount + ' EUR';
    document.getElementById('reservationPeriod').textContent = 'Para el mes: ' + reservationPeriod;

    (async function () {
      const { clientSecret } = await fetch('./stripe_checkout/create.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ registers }),
      }).then((r) => r.json());

      elements = stripe.elements({ clientSecret });

      registers.forEach((element, i) => {
        registers[i].clientSecret = clientSecret;
      });

      const paymentElement = elements.create('payment');

      IncludeRegistersOnDataBase(registers).then((status) => {
        if (status.finalStatus == 200) {
          paymentElement.mount('#payment-element');

          setTimeout(function () {
            document.getElementById('submit').disabled = false;
          }, 4000);
        } else {
          alert('Error al intentar registrar datos en el Sistema');
          console.log(status);
        }
      });
    })();
  });
} // mount the elements of pay of stripe

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

document.querySelector('#payment-form').addEventListener('submit', handleSubmit);
async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);
  clearMessageBox();

  getInfoPlazaPeriod(formDatas).then((infoPlazaPeriod) => {
    if (infoPlazaPeriod.plazas >= infoPlazaPeriod.countSelectedProcedures) {
      (async function () {
        try {
          await stripe
            .confirmPayment({
              elements,
              confirmParams: {
                receipt_email: document.getElementById('email').value,
              },
              redirect: 'if_required',
            })
            .then(function (result) {
              if (result.paymentIntent) {
                confirmPaymentOnDB(result, registers).then((confirmation) => {
                  if (confirmation.DB_registerUpdated === true) {
                    window.location.href =
                      '/reservas/index.php?checkout=Finished&nif_session=' +
                      formDatas[0]['nif'] +
                      '&payment_intent=' +
                      result.paymentIntent.id +
                      '&payment_intent_client_secret=' +
                      result.paymentIntent.client_secret;
                  } else {
                    var message =
                      'Erro no momento de registrar o pagamento no Bando de Dados. Se você receber o Recibo por e-mail, por favor entre em contato com a recepção da Tamara Freitas para completar seu registro. | Menssagem de erro: ' +
                      confirmation.DB_registerUpdated;
                    showMessage(message, 'status_success');
                  }
                });
              }

              if (result.error) {
                if (result.error.type === 'card_error' || result.error.type === 'validation_error') {
                  showMessage(result.error.message, 'status_error');
                } else {
                  showMessage('An unexpected error occured.', 'status_error');
                }
                setLoading(false);
              }
            });
        } catch (err) {
          console.log(err);
        }
      })();
    } else {
      alert(
        'Mientras completaba sus datos de pago se cubrieron algunas plazas, y en esto momento no hay suficientes plazas para la cantidad de procedimientos que deseas realizar. Es posible que ya estén abiertas las plazas para el proximo mes. El SitioWeb volverá a la pagina de início y, si es posible, intente nuevamente.',
      );
      setLoading(false);
      window.location.href = '/reservas';
    }
  });
} // Fetches a payment intent and captures the client secret

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////

function showMessage(messageText, statusClass) {
  const messageContainer = document.querySelectorAll('#payment-message');

  for (let i = 0; i < messageContainer.length; i++) {
    messageContainer[i].classList.remove('hidden');
    messageContainer[i].classList.remove('status_warning');
    messageContainer[i].classList.remove('status_error');
    messageContainer[i].classList.remove('status_success');
    messageContainer[i].classList.add(statusClass);

    messageContainer[i].insertAdjacentHTML('afterbegin', messageText);
  }
} // Show message of status

function clearMessageBox() {
  const messageContainer = document.querySelectorAll('#payment-message');

  for (let i = 0; i < messageContainer.length; i++) {
    messageContainer[i].classList.add('hidden');
    messageContainer[i].textContent = '';
    while (messageContainer[i].firstChild) {
      messageContainer[i].removeChild(messageContainer[i].firstChild);
    }
  }
} // Hide message of status

function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector('#submit').disabled = true;
    document.querySelector('#spinner').classList.remove('hidden');
    document.querySelector('#button-text').classList.add('hidden');
  } else {
    document.querySelector('#submit').disabled = false;
    document.querySelector('#spinner').classList.add('hidden');
    document.querySelector('#button-text').classList.remove('hidden');
  }
} // Show a spinner on payment submission
