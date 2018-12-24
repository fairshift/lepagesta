import React from 'react';
import * as PropTypes from 'prop-types';

import { FormattedMessage, intlShape, injectIntl } from 'react-intl';
import messages from './messages';

import { Form, Icon, Input, Button } from 'antd';

import { validateEmail } from '../../utils/functions';


const FormItem = Form.Item;

function hasErrors(fieldsError) {
  return Object.keys(fieldsError).some(field => fieldsError[field]);
}


class Login extends React.Component {

  state = {
    formOrientation: 'vertical'
  }

  componentDidMount() {
    // To disabled submit button at the beginning.
    this.props.form.validateFields();
  }

  handleSubmit = (e) => {
    e.preventDefault();
    this.props.form.validateFields((err, values) => {
      if (!err) {
        console.log('Received values of form: ', values);
      }
    });
  }

  usernameValidator = (rule, value, callback) => {
  	const { getFieldValue } = this.props.form;
    
    if(typeof value !== 'undefined'){
      if(value.indexOf("@") > 0 && (!validateEmail(value) || value.length < 5)){
      	callback(this.props.intl.formatMessage(messages.user_email_err));
      } else {
      	if(value.length < 3 && value.length > 0){
      		callback(this.props.intl.formatMessage(messages.username_err_length));
      	}
      }
    }

    callback();
  }

  render() {
  	const intl = this.props.intl
    const {
      getFieldDecorator, getFieldsError, getFieldError, isFieldTouched,
    } = this.props.form;

    // Only show error after a field is touched.
    const userNameError = isFieldTouched('username') && getFieldError('username');
    const passwordError = isFieldTouched('password') && getFieldError('password');
    return (
      <div>
        <Form layout={this.state.formOrientation} onSubmit={this.handleSubmit}>
          <FormItem
            validateStatus={userNameError ? 'error' : ''}
            help={userNameError || ''}
          >
            {getFieldDecorator('username', {
              rules: [{ required: true, message: intl.formatMessage(messages.username_err_length) },
              				{ validator: this.usernameValidator }],
            })(
              <Input prefix={<Icon type="user" 
              	style={{ color: 'rgba(0,0,0,.25)' }} />} 
              	placeholder={intl.formatMessage(messages.user)} />
            )}
          </FormItem>
          <FormItem
            validateStatus='validating'
            help={passwordError || ''}
          >
            {getFieldDecorator('password', {
              rules: [{ min: 8, message: intl.formatMessage(messages.password_err_length) }],
            })(
              <Input prefix={<Icon type="lock" style={{ color: 'rgba(0,0,0,.25)' }} />} 
                      type="password" 
                      placeholder={intl.formatMessage(messages.password)} />
            )}
          </FormItem>

          <FormItem>
            <Button
              type="primary"
              htmlType="submit"
              disabled={hasErrors(getFieldsError())}
            >
              { intl.formatMessage(messages.login) }
            </Button>
          </FormItem>
        </Form>

        {(typeof this.props.disableExternal !== 'undefined') ? '' : (

          <Form layout="vertical">

            <div className="form-label">
              <FormattedMessage {...messages.external_auth} />
            </div>

            <FormItem>
              <Button href="/oauth/twitter" icon="twitter">
                { intl.formatMessage(messages.signin_twitter) }
              </Button>
            </FormItem>
            <FormItem>
              <Button href="test" icon="facebook">
                { intl.formatMessage(messages.signin_facebook) }
              </Button>
            </FormItem>
            <FormItem>
              <Button href="test" icon="google">
                { intl.formatMessage(messages.signin_gmail) }
              </Button>
            </FormItem>
          </Form>

        )}

      </div>
    );
  }
}

const LoginForm = injectIntl(Form.create()(Login));

export default LoginForm;