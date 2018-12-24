/*
 * HomePage
 *
 * This is the first thing users see of our App, at the '/' route
 */

import React from 'react';
import PropTypes from 'prop-types';

import { connect } from 'react-redux';
import { compose } from 'redux';

import { Container, Row, Col } from 'reactstrap';
import {
  Form, Input, Slider, Badge, Button, Icon, Upload, Modal, Spin, Progress, Menu
} from 'antd';
import InputRange from 'react-input-range';


const FormItem = Form.Item;
const { TextArea } = Input;
const SubMenu = Menu.SubMenu;

export class FormParsed extends React.Component {

  state = {
    steps: 0,
    save_every_N_steps: 5,
    steps_percent: 0,

    openVisible: false,
    openIcon: 'up-circle',
    upload_previewVisible: false,
    upload_previewImage: '',
    upload_fileList: [{
      uid: '-1',
      name: 'xxx.png',
      status: 'done',
      url: 'https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png',
    }],

    sideMenuCollapsed: false,
  }

  toggleMenuCollapsed = () => {
    this.setState({
      sideMenuCollapsed: !this.state.sideMenuCollapsed
    });
  }

  onTextAreaInput = (e) => {
    this.setState({steps: this.state.steps + 1});
    this.setState({steps_percent: this.state.save_every_n_steps / this.state.steps})
    console.log(e.target.value);
  }

  sliderTip = (value) => {
    return 'Saving in '+ (this.state.save_every_N_steps - this.state.steps) +' steps'
  }

  onUploadCancel = () => this.setState({ upload_previewVisible: false })

  onUploadPreview = (file) => {
    this.setState({
      upload_previewImage: file.url || file.thumbUrl,
      upload_previewVisible: true,
    });
  }

  onUploadChange = (e) => {
    console.log(e);
    //this.setState({ fileList })
  }

  onFileRemove = (file) => console.log(file)

  render() {
    const { getFieldDecorator } = this.props.form;
    const { steps, steps_percent, save_every_N_steps } = this.state;
    const { openVisible, openIcon,
            upload_previewVisible, upload_previewImage, upload_fileList } = this.state;

    const uploadButton = (
      <div>
        <Icon type="plus" />
        <div className="ant-upload-text">Upload</div>
      </div>
    );

    return (

          <Button type="primary" onClick={this.toggleMenuCollapsed} style={{ marginBottom: 16 }}>
            <Icon type={this.state.sideMenuCollapsed ? 'menu-unfold' : 'menu-fold'} />
          </Button>
          <Menu
            defaultSelectedKeys={['1']}
            defaultOpenKeys={['sub1']}
            mode="inline"
            theme="dark"
            inlineCollapsed={this.state.sideMenuCollapsed}
          >
            <Menu.Item key="1">
              <Icon type="pie-chart" />
              <span>Option 1</span>
            </Menu.Item>
            <Menu.Item key="2">
              <Icon type="desktop" />
              <span>Option 2</span>
            </Menu.Item>
            <Menu.Item key="3">
              <Icon type="inbox" />
              <span>Option 3</span>
            </Menu.Item>
            <SubMenu key="sub1" title={<span><Icon type="mail" /><span>Navigation One</span></span>}>
              <Menu.Item key="5">Option 5</Menu.Item>
              <Menu.Item key="6">Option 6</Menu.Item>
              <Menu.Item key="7">Option 7</Menu.Item>
              <Menu.Item key="8">Option 8</Menu.Item>
            </SubMenu>
            <SubMenu key="sub2" title={<span><Icon type="appstore" /><span>Navigation Two</span></span>}>
              <Menu.Item key="9">Option 9</Menu.Item>
              <Menu.Item key="10">Option 10</Menu.Item>
              <SubMenu key="sub3" title="Submenu">
                <Menu.Item key="11">Option 11</Menu.Item>
                <Menu.Item key="12">Option 12</Menu.Item>
              </SubMenu>
            </SubMenu>
          </Menu>
        </div>

        <Container>
        { (openVisible == true) ?
          <Row className={(openVisible == true) ? '' : 'hidden'}>
            <Col>
              <div className="clearfix">
                <Upload
                  action="//jsonplaceholder.typicode.com/posts/"
                  listType="picture-card"
                  fileList={upload_fileList}
                  onPreview={this.onUploadPreview}
                  onChange={this.onUploadChange}
                  onRemove={this.onFileRemove}
                >
                  {upload_fileList.length >= 3 ? null : uploadButton}
                </Upload>
                <Modal visible={upload_previewVisible} footer={null} onCancel={this.onUploadCancel}>
                  <img alt="example" style={{ width: '100%' }} src={upload_previewImage} />
                </Modal>
              </div>
            </Col>
          </Row>
        : null }

          <Row>
            <Col xs={1} md={3} lg={1} style={{ 'text-align': 'center' }}>
              <Button type="primary" shape="circle" icon={openIcon} size="large" />
            </Col>
            <Col xs={6} md={8} lg={8} className="sliderHasBadge">
              <Progress type="circle" status="active" percent={steps_percent} />
              <Slider value={typeof steps === 'number' ? steps : 0} 
                tipFormatter={this.sliderTip} 
                min={0} max={save_every_N_steps} 
                onChange={this.onChange} />

              <Badge count={steps} showZero overflowCount={999} 
                style={{ 'float': 'right', backgroundColor: '#fff', color: '#999', boxShadow: '0 0 0 1px #d9d9d9 inset' }} />
              <Spin indicator={<Icon type="loading" style={{ fontSize: 24 }} spin />} />
            </Col>
            <Col xs={3} md={3} lg={3} style={{ 'text-align': 'right' }}>
              <Button.Group size='large'>
                <Button type="primary">
                  Save
                </Button>
                <Button type="primary">
                  <Icon type="download" />
                </Button>
              </Button.Group>
            </Col>
          </Row>
          <Row>
            <Col xs={12}>
              <Form>
                <FormItem>
                  <TextArea onKeyDown={e => this.onTextAreaInput(e)} 
                            rows={10} />
                </FormItem>
              </Form>
            </Col>
          </Row>
        </Container>
      </div>
    );
  }
}

export const WrappedFormParsed = Form.create()(FormParsed);

export default WrappedFormParsed

/*
FormParsed.propTypes = {
  loading: PropTypes.bool,
  error: PropTypes.oneOfType([PropTypes.object, PropTypes.bool]),
  repos: PropTypes.oneOfType([PropTypes.array, PropTypes.bool]),
  onSubmitForm: PropTypes.func,
  username: PropTypes.string,
  onChangeUsername: PropTypes.func
}

export function mapDispatchToProps (dispatch) {
  return {
    onChangeUsername: evt => dispatch(changeUsername(evt.target.value)),
    onSubmitForm: evt => {
      if (evt !== undefined && evt.preventDefault) evt.preventDefault()
      dispatch(loadRepos())
    }
  }
}

const mapStateToProps = createStructuredSelector({
  repos: makeSelectRepos(),
  username: makeSelectUsername(),
  loading: makeSelectLoading(),
  error: makeSelectError()
})

const withConnect = connect(
  mapStateToProps,
  mapDispatchToProps
)

const withReducer = injectReducer({ key: 'home', reducer })
const withSaga = injectSaga({ key: 'home', saga })

export default compose(
  withReducer,
  withSaga,
  withConnect
)(HomePage)
*/