import React from "react"

import lowdb from 'lowdb';
import LocalStorage from 'lowdb/adapters/LocalStorage'

const db = lowdb( new LocalStorage('db') );
const databaseContext = React.createContext();

export {db, databaseContext};