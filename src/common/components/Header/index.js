import React from 'react';

import A from './A';
import Img from './Img';
import Banner from './banner.jpg';

import { Link } from "@reach/router";


/* Looks like a sunset from websites of Emotion.js and Styled Components
import styled from "styled-components";
const StyledButton = styled(Link)`
  min-width: 12rem;
  margin: 0 auto 20px;
  padding: 16px;
  border-radius: 5px;
  text-decoration: none;
  color: #1D2029;
  &:hover {
    opacity: 0.95;
  }
  background: linear-gradient(90deg, #D26AC2, #46C9E5);
  @media (min-width: 768px) {
    margin: 0 20px 0 0;
    &:last-child {
      margin: 0;
    }
  }
`; */

/* eslint-disable react/prefer-stateless-function */
class Header extends React.Component {

  render() {
    return (
      <div>
        <A href="https://twitter.com/mxstbr">
          <Img src={Banner} alt="react-boilerplate - Logo" />
        </A>
      </div>
    );
  }
}

export default Header;