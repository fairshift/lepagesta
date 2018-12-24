import ApolloClient from 'apollo-client';
import { ApolloProvider, renderToStringWithData } from 'react-apollo';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloLink } from 'apollo-link';
import { HttpLink } from 'apollo-link-http';
import fetch from 'isomorphic-fetch';
import { createPersistedQueryLink } from 'apollo-link-persisted-queries';

import {
  errorLink,
  subscriptionLink,
  requestLink,
  queryOrMutationLink,
} from './graphqlApolloLinks';

const links = [
  errorLink,
  queryOrMutationLink({
    fetch,
    uri: `http://localhost:4000`,
  }),
];

const clientInit = () => {

	// support APQ in production
	if (process.env.NODE_ENV === 'production') {
	  links.unshift(createPersistedQueryLink());
	}

	return new ApolloClient({
		ssrMode: true,
		link: ApolloLink.from(links),
		cache: new InMemoryCache(),
	})
}

export { clientInit, links, ApolloProvider };