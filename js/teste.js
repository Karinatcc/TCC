document.addEventListener('DOMContentLoaded', () => {
  // =================== CHAT PRINCIPAL ===================
  const chatContainer = document.querySelector('.chat-container');
  if (chatContainer) {
    const messages = [
      { from: 'bot', text: 'Olá! Como posso te ajudar hoje?' },
      { from: 'user', text: 'Oi! Quero saber mais sobre o produto.' },
      { from: 'bot', text: 'Claro! Nosso produto é incrível e tem garantia de 1 ano.' },
    ];

    function showMessage(index) {
      if (index >= messages.length) return;

      const msg = messages[index];

      if (msg.from === 'bot') {
        const typing = document.createElement('div');
        typing.classList.add('typing');
        chatContainer.appendChild(typing);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        setTimeout(() => {
          typing.remove();

          const div = document.createElement('div');
          div.classList.add('chat-message', msg.from);
          div.textContent = msg.text;
          chatContainer.appendChild(div);

          setTimeout(() => {
            div.style.opacity = 1;
            div.style.transform = 'translateY(0)';
          }, 50);

          chatContainer.scrollTop = chatContainer.scrollHeight;
          showMessage(index + 1);
        }, 1500);
      } else {
        setTimeout(() => {
          const div = document.createElement('div');
          div.classList.add('chat-message', msg.from);
          div.textContent = msg.text;
          chatContainer.appendChild(div);

          setTimeout(() => {
            div.style.opacity = 1;
            div.style.transform = 'translateY(0)';
          }, 50);

          chatContainer.scrollTop = chatContainer.scrollHeight;
          showMessage(index + 1);
        }, 800);
      }
    }
    showMessage(0);
  }

  // =================== MINI CHAT FLUTUANTE ===================
  const miniChatBox = document.getElementById('mini-chat-box');
  const miniChatMessages = document.getElementById('mini-chat-messages');
  if (miniChatBox && miniChatMessages) {
    const miniChatData = [
      { from: 'bot', text: 'Olá! Bem-vindo ao atendimento virtual.' },
      { from: 'user', text: 'Boa tarde! Quais são os horários de entrega?' },
      { from: 'bot', text: 'Entregamos de segunda a sexta, das 9h às 18h.' },
    ];

    function showMiniChatMessage(index) {
      if (index >= miniChatData.length) return;

      const msg = miniChatData[index];

      if (msg.from === 'bot') {
        const typing = document.createElement('div');
        typing.classList.add('typing');
        miniChatMessages.appendChild(typing);
        miniChatMessages.scrollTop = miniChatMessages.scrollHeight;

        setTimeout(() => {
          typing.remove();

          const div = document.createElement('div');
          div.classList.add('chat-message', msg.from);
          div.textContent = msg.text;
          miniChatMessages.appendChild(div);

          setTimeout(() => {
            div.style.opacity = 1;
            div.style.transform = 'translateY(0)';
          }, 50);

          miniChatMessages.scrollTop = miniChatMessages.scrollHeight;
          showMiniChatMessage(index + 1);
        }, 1500);
      } else {
        setTimeout(() => {
          const div = document.createElement('div');
          div.classList.add('chat-message', msg.from);
          div.textContent = msg.text;
          miniChatMessages.appendChild(div);

          setTimeout(() => {
            div.style.opacity = 1;
            div.style.transform = 'translateY(0)';
          }, 50);

          miniChatMessages.scrollTop = miniChatMessages.scrollHeight;
          showMiniChatMessage(index + 1);
        }, 800);
      }
    }

    // Função global
    window.toggleChat = function () {
      const isHidden = miniChatBox.style.display === 'none';
      miniChatBox.style.display = isHidden ? 'flex' : 'none';
      if (isHidden) {
        miniChatMessages.innerHTML = '';
        showMiniChatMessage(0);
      }
    };
  }

  // =================== SUBMENU ===================
  const submenuItems = document.querySelectorAll('.menu li.has-submenu');
  submenuItems.forEach(item => {
    const link = item.querySelector('a');
    const submenu = item.querySelector('.submenu');
    if (link && submenu) {
      link.addEventListener('mouseenter', () => {
        submenu.style.display = 'block';
      });
      item.addEventListener('mouseleave', () => {
        submenu.style.display = 'none';
      });
    }
  });

  // =================== MOSTRAR/OCULTAR SENHA ===================
  const toggle = document.querySelector('.toggle-olho');
  const senha = document.querySelector('#senha');
  if (toggle && senha) {
    let mostrando = false;
    toggle.addEventListener('click', () => {
      mostrando = !mostrando;
      senha.type = mostrando ? 'text' : 'password';
      toggle.innerHTML = mostrando
        ? '<path fill="#555" d="M12 6a9.77 9.77 0 0 1 9.54 7 9.77 9.77 0 0 1-19.08 0A9.77 9.77 0 0 1 12 6m0 2a7.73 7.73 0 0 0-7.45 5 7.73 7.73 0 0 0 14.9 0A7.73 7.73 0 0 0 12 8m0 2a3 3 0 0 1 0 6a3 3 0 0 1 0-6z"/>'
        : '<path fill="#555" d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 12.5c-2.48 0-4.5-2.02-4.5-4.5S9.52 8 12 8s4.5 2.02 4.5 4.5S14.48 17 12 17zm0-7c-1.38 0-2.5 1.12-2.5 2.5S10.62 15 12 15s2.5-1.12 2.5-2.5S13.38 10 12 10z"/>';
    });
  }

  // =================== FAQ ===================
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach((item) => {
    const button = item.querySelector('.faq-question');
    if (!button) return;
    button.addEventListener('click', () => {
      faqItems.forEach((i) => {
        if (i !== item) i.classList.remove('active');
      });
      item.classList.toggle('active');
    });
  });

  // =================== SCROLL SUAVE PARA FAQ ===================
  const perguntasFrequentesCard = document.querySelector('.card:nth-child(2)');
  if (perguntasFrequentesCard) {
    perguntasFrequentesCard.style.cursor = 'pointer';
    perguntasFrequentesCard.addEventListener('click', () => {
      const faqSection = document.querySelector('.section-faq');
      if (faqSection) {
        // Scroll suave personalizado com mais suavidade
        const targetPosition = faqSection.getBoundingClientRect().top + window.pageYOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        const duration = 1200; // Aumentei a duração para ficar mais suave
        let startTime = null;

        function animation(currentTime) {
          if (startTime === null) startTime = currentTime;
          const timeElapsed = currentTime - startTime;
          const run = easeInOutQuad(timeElapsed, startPosition, distance, duration);
          window.scrollTo(0, run);
          if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        // Função de easing para movimento mais suave
        function easeInOutQuad(t, b, c, d) {
          t /= d / 2;
          if (t < 1) return c / 2 * t * t + b;
          t--;
          return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
      }
    });
  }
});