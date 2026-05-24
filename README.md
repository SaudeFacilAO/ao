# Saúde Fácil - Painel Administrativo Hospitalar

Uma interface completa e moderna de painel administrativo para gestão hospitalar e telemedicina, desenvolvida com **HTML, CSS e JavaScript puro**.

## 🎯 Características

### 📊 Funcionalidades Principais

- **Dashboard**: Visão geral do hospital com estatísticas em tempo real
- **Indicadores de Desempenho**: Monitoramento de métricas hospitalares
- **Relatórios Gerenciais**: Geração e exportação de relatórios personalizados
- **Gestão de Usuários**: Administração de médicos e secretários
- **Agenda Geral**: Calendário e consultas agendadas
- **Logs de Auditoria**: Rastreamento de atividades no sistema
- **Central de Notificações**: Gerenciamento de notificações
- **Configurações**: Perfil, segurança e preferências do usuário
- **Suporte**: FAQ, formulário de contato e relato de problemas

### 🎨 Design & UX

- **Design Responsivo**: Funciona perfeitamente em mobile, tablet e desktop
- **Tema Escuro/Claro**: Suporte completo a ambos os temas
- **Paleta Profissional**: Azul e branco para transmitir confiança e profissionalismo
- **Animações Suaves**: Transições e efeitos visuais agradáveis
- **Ícones Modernos**: Interface intuitiva e fácil de usar

## 📁 Estrutura de Arquivos

```
public/
├── index.html          # Estrutura HTML completa
├── styles.css          # Estilos CSS com design responsivo
├── script.js           # JavaScript puro para interatividade
└── README.md           # Este arquivo
```

## 🚀 Como Usar

### 1. Abrir a Aplicação

Simplesmente abra o arquivo `index.html` em um navegador moderno:

```bash
# Opção 1: Clique duplo no arquivo index.html
# Opção 2: Use um servidor local (recomendado)
python3 -m http.server 8000
# Acesse: http://localhost:8000/public/
```

### 2. Navegação

- **Sidebar**: Menu lateral com todas as opções do painel
- **Topo**: Barra superior com busca, notificações e perfil do usuário
- **Menu Hambúrguer**: Em dispositivos móveis, clique no ☰ para abrir o menu

### 3. Funcionalidades Principais

#### Dashboard
- Visualize estatísticas de consultas
- Acompanhe indicadores em tempo real
- Veja atividades recentes do hospital

#### Relatórios
1. Configure os filtros (data, médico, especialidade)
2. Selecione o tipo de relatório desejado
3. Clique em "Gerar Relatório"
4. Exporte (CSV) ou imprima o resultado

#### Gestão de Usuários
1. Clique em "+ Adicionar Usuário"
2. Preencha o formulário com as informações
3. Selecione a função (Médico ou Secretário)
4. Salve as alterações

#### Configurações
- Edite seu perfil pessoal
- Altere sua senha
- Configure o tema (escuro/claro)
- Gerencie preferências de notificações

## 🎨 Customização

### Alterar Cores

Edite as variáveis CSS em `styles.css`:

```css
:root {
    --primary-color: #2563eb;    /* Azul principal */
    --accent-color: #0ea5e9;      /* Cyan */
    --success-color: #10b981;     /* Verde */
    --danger-color: #ef4444;      /* Vermelho */
}
```

### Modificar Textos

Os textos estão em português. Para mudar o idioma, edite `index.html` e `script.js`.

### Adicionar Novas Páginas

1. Adicione um novo item no menu (sidebar)
2. Crie um novo `div` com `class="page-content"` e um `id`
3. Adicione entrada no objeto `pageTitles` em `script.js`

## 📱 Responsividade

A interface se adapta automaticamente para:

- **Desktop** (>1024px): Layout completo com sidebar expandida
- **Tablet** (768px - 1024px): Sidebar reduzida
- **Mobile** (<768px): Menu hambúrguer com sidebar colapsável
- **Smartphone** (<480px): Layout otimizado para tela pequena

## ⌨️ Atalhos e Interações

- **Clique no menu**: Navega entre as páginas
- **Menu hambúrguer**: Abre/fecha o menu lateral em mobile
- **Perfil (canto superior direito)**: Menu de usuário
- **Notificações**: Clique no sino para gerenciar notificações
- **Busca**: Use a barra de busca para filtrar

## 🛠️ Recursos Técnicos

### Sem Dependências Externas
- ✅ HTML5 semântico
- ✅ CSS3 com variáveis e flexbox
- ✅ JavaScript puro (Vanilla JS)
- ✅ Canvas para gráficos simples
- ✅ LocalStorage para persistência

### Compatibilidade

Funciona em navegadores modernos:
- Chrome/Edge 88+
- Firefox 85+
- Safari 14+
- Opera 74+

## 📊 Gráficos

Os gráficos são desenhados com Canvas puro, sem bibliotecas externas:

- **Gráfico de Barras**: Consultas por dia
- **Gráfico de Pizza**: Especialidades mais procuradas
- **Gráfico de Barras Horizontal**: Consultas por médico

## 💾 Persistência de Dados

Alguns dados são salvos no navegador usando `localStorage`:

- Tema preferido (claro/escuro)
- Preferências de notificações
- Dados de formulários (temporário)

## 🔒 Notas de Segurança

Esta é uma interface de demonstração. Para produção:

- Implemente autenticação real no servidor
- Use HTTPS para todas as conexões
- Implemente validação no servidor
- Adicione proteção contra CSRF
- Sanitize todos os inputs do usuário

## 📝 Melhorias Futuras

- [ ] Integração com API backend
- [ ] Autenticação real
- [ ] Gráficos interativos com biblioteca
- [ ] Exportação de PDF
- [ ] Múltiplos idiomas
- [ ] Notificações em tempo real com WebSocket
- [ ] Offline mode

## 🤝 Suporte

Para dúvidas ou problemas:

1. Verifique se você está usando um navegador moderno
2. Limpe o cache do navegador
3. Consulte o console (F12) para mensagens de erro
4. Verifique a seção "Suporte" dentro da aplicação

## 📄 Licença

Este projeto é fornecido como exemplo educacional.

## ✨ Características Especiais

### Dark Mode
Ativa automaticamente e persiste nas visitas futuras. Clique em Configurações > Tema para alternar.

### Responsividade Mobile-First
Desenvolvido com abordagem mobile-first para melhor experiência em dispositivos menores.

### Acessibilidade
Inclui atributos ARIA, labels adequados e navegação por teclado.

### Performance
- Sem dependências externas
- Carregamento rápido
- Animações suaves com CSS3

---

**Desenvolvido com ❤️ para Saúde Fácil**

Versão 1.0 - Março 2024
