import { injectGlobal } from "styled-components";

/* eslint no-unused-expressions: 0 */
injectGlobal`

  html,
  body {
    height: 100%;
    width: 100%;
  }

  body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  }

  body.fontLoaded {
    font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
  }

  p,
  label {
    font-family: Georgia, Times, 'Times New Roman', serif;
    line-height: 1.5em;
  }

  #root {
    background-color: #282923;
    height: 100%
  }
  .hidden {
    display: none
  }

  .react-parallax {
    height: 100%
  }


  #app {
    background-color: #000;
    min-height: 100%;
    min-width: 100%;
  }

  .row {
    background-color: #FFF
  }


  div.headroom-wrapper {
    background-color: #282923;
    width: 100%
  }
  .menuFullWidth .headerWrapper {
    padding-left: 
  }


  .main-container, .wrapper,
  .main-container .wrapper,
  .main-container .container,
  .menuFullWidth .headerWrapper {
    margin: 0px auto
    padding-left: 0px;
    padding-right: 0px;
  }
  @media (min-width: 1200px) {
    .main-container, .wrapper,
    .container.wrapper,
    .container.content,
    .container.headerWrapper, 
    .container.footerWrapper {
      max-width: 1100px;
    }
  }
  @media (min-width: 1100px) {
    .main-container, .wrapper,
    .container.wrapper,
    .container.content,
    .container.headerWrapper, 
    .container.footerWrapper {
      max-width: 900px;
    }
  }
  @media (min-width: 768px) and (max-width: 110px) {
    .main-container, .wrapper,
    .container.wrapper,
    .container.content,
    .container.headerWrapper, 
    .container.footerWrapper {
      max-width: 675px;
    }
  }
  @media (min-width: 576px) and (max-width: 768px) {
    .main-container, .wrapper,
    .container.wrapper,
    .container.content,
    .container.headerWrapper, 
    .container.footerWrapper {
      max-width: 100%;
    }
  }
  @media (max-width: 576px) {
    .main-container, .wrapper,
    .container.wrapper,
    .container.content,
    .container.headerWrapper, 
    .container.footerWrapper {
      width: 100%;
    }
  }
  .container .row {
    margin-left: 0px
    margin-right: 0px
  }
  .container .col {
    padding-left: 0px
    padding-right: 0px
  }
  .container.content .col {
    padding-left: 15px
    padding-right: 15px
  }


  @media (min-width: 768px) {
    .navbar.menuMain {
      padding-left: 0px;
      padding-right: 0px;
    }
    .navbar.menuMain a.nav-link {
      padding-right: 0px;
      padding-left: 1rem;
    }
  }
  .Navbar-SignIn .dropdown-menu {
    min-width: 20rem
  }
  .Navbar-SignIn form.ant-form {
    padding-left: 8px;
    padding-right: 8px;
  }
  .Navbar-SignIn .form-label {
    padding: 12px 8px 5px 8px;
    text-align: center;
  }
  .Navbar-SignIn form.ant-form .ant-row.ant-form-item {
    margin-bottom: 0px
  }
  .Navbar-SignIn form.ant-form .ant-btn {
    width: 100%
  }
  .Navbar-SignIn form.ant-form .ant-btn i {
    position: absolute;
    left: 12px;
    top: 7px;
  }

  // Main container
  .wrapper.container {
    padding-left: 0px;
    padding-right: 0px
  }
  .container.padding-top div.row > div {
    padding-top: 25px
  }


//
// Components
//

  // Form controls
  // 1. Slider (antd)
  .ant-slider .ant-slider-track {
    transition: width 200ms ease-in-out;
  }

  // 1.1. Slider with a badge
  .sliderHasBadge > .ant-badge {
    float: right;
    position: relative;
    right: -37px;
    top: -26px
  }


  // 2. Button icon (antd)
  .ant-btn-lg > i {
    top: -3px;
    position: relative;
  }


  // 3. Side-menu (> md), Sticky hiding top menu (< md) 
  //   (Bootstrap layout & antd)
  div.sideMenu > div > ul.ant-menu-inline-collapsed {
    width: auto;
  }

  div.sideMenu {
    overflow-x: hidden
  }
  div.sideMenu div.menu {
    position: relative
    z-index: 20;
    float: left
    width: 80px
    overflow: visible
  }
  div.sideMenu div.content {
    overflow: hidden
  }
  div.sideMenu div.menu > div button.ant-btn {
    width: 100%;
    border-radius: 0px 4px 0px 0px;
    margin-bottom: 0px;
    padding-left: 31px;
    text-align: left
  }

  div.sideMenu div.menu.open > div {
    width: 300px
    box-shadow: 5px 3px 5px #888;
  }
  div.sideMenu div.menu.open > div button.ant-btn {
    padding-left: 271px
  }

  div.sideMenu .ant-menu-item .anticon {
    position: relative;
    top: -3px;
  }
  div.sideMenu div.menu.open > div button.ant-btn .anticon {
    position: relative;
    top: -2px;
  }
  

  // Matrix container
  div.matrixContainer,
  div.matrixContainer canvas {
    width: 100%;
    height: 100%
  }



//
// Transitions
//

  .transition,
  {
      -moz-transition: all 2s ease-out;  /* FF4+ */
      -o-transition: all 2s ease-out;  /* Opera 10.5+ */
      -webkit-transition: all 2s ease-out;  /* Saf3.2+, Chrome */
      -ms-transition: all 2s ease-out;  /* IE10 */
      transition: all 2s ease-out; 
  }



  .hiddenTransition { opacity : 0; transition:opacity 0.5s; }

`;
