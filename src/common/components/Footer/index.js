import React from "react";
import { FormattedMessage } from "react-intl";

import A from "../A";
import LocaleToggle from "../../containers/LocaleToggle";
import messages from "./messages";

function Footer() {
  return (
    <div>
      <section>
        <FormattedMessage {...messages.licenseMessage} />
      </section>
      <section>
        <LocaleToggle />
      </section>
      <section>
        <FormattedMessage
          {...messages.authorMessage}
          values={{
            author: <A href="https://twitter.com/mxstbr">Max Stoiber</A>
          }}
        />
      </section>
    </div>
  );
}

export default Footer;
