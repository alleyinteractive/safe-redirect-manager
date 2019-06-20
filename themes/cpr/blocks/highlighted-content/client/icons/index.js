/* eslint-disable consistent-return */
/* eslint-disable react/jsx-closing-tag-location */
/* eslint-disable max-len */
/* eslint-disable react/prefer-stateless-function */
/* eslint-disable react/no-multi-comp */
import camelCase from 'lodash/camelCase';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import classnames from 'classnames/dedupe';
import './icons.scss';

const { Component } = wp.element;
const { __ } = wp.i18n;

const {
  Dropdown,
  Tooltip,
  BaseControl,
  TextControl,
} = wp.components;

library.add(fab, fas);
const allFabIcons = Object.values(fab);
const allFasIcons = Object.values(fas);
const allIconData = allFabIcons.concat(allFasIcons);

const Icon = (props) => {
  const {
    className,
    iconData,
    onClick,
    active,
  } = props;

  // These icons cause problems, so let's not try to render them.
  // camelCase() transforms `fa-500px` to fa500Px, which does not work.
  // The fontawesome logo is huge, breaks the layout and why is it even there...
  if (
    iconData &&
    ('500px' === iconData.iconName || 'font-awesome-logo-full' === iconData.iconName)
  ) {
    return null;
  }

  const iconPrefix = iconData ? iconData.prefix : '';
  const iconName = iconData ? `fa-${iconData.iconName}` : '';
  const iconId = 'fas' === iconPrefix ? fas[camelCase(iconName)] : fab[camelCase(iconName)];

  return (
    <span
      className={classnames(
        className,
        'cpr-component-icon-picker-preview',
        'cpr-component-icon-picker-button',
        { 'cpr-component-icon-picker-preview-clickable': onClick },
        { 'cpr-component-icon-picker-button-active': active },
      )}
      onClick={onClick}
      onKeyPress={() => {}}
      role="button"
      tabIndex={0}
      data-iconName={camelCase(iconName)}
      data-iconPrefix={iconPrefix}
    >
      <FontAwesomeIcon icon={iconId} />
    </span>
  );
};

class IconPickerDropdown extends Component {
  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);

    this.state = {
      search: '',
    };
  }

  render() {
    const {
      label,
      className,
      onChange,
      value,
      renderToggle,
    } = this.props;

    const dropdown = (
      <Dropdown
        className={className}
        renderToggle={renderToggle}
        renderContent={() => {
          const result = allIconData.map((icon) => {
            if (
              ! this.state.search ||
                (this.state.search && - 1 < icon.keys.indexOf(this.state.search))
            ) {
              return (
                <Icon
                  key={icon.iconName}
                  active={icon.iconName === value}
                  iconData={icon}
                  onClick={() => {
                    onChange(icon);
                  }}
                />
              );
            }

            return '';
          });

          return (
            <div className="cpr-component-icon-picker">
              <div>
                <TextControl
                  value={value}
                  onChange={(newClass) => {
                    onChange(newClass);
                  }}
                  placeholder={__('Icon class', 'cpr')}
                  type="hidden"
                />
                <TextControl
                  label={__('Search icon')}
                  value={this.state.search}
                  onChange={(searchVal) => {
                    return this.setState({ search: searchVal });
                  }}
                  placeholder={__('Type to search...', 'cpr')}
                />
              </div>
              <div className="cpr-component-icon-picker-list-wrap">
                <div className="cpr-component-icon-picker-list">
                  { result }
                </div>
              </div>
            </div>
          );
        }}
      />
    );

    return label ? (
      <BaseControl
        label={label}
        className={className}
      >
        { dropdown }
      </BaseControl>
    ) : (
      dropdown
    );
  }
}

export default class IconPicker extends Component {
  render() {
    const {
      value,
      onChange,
      label,
    } = this.props;

    return (
      <IconPickerDropdown
        label={label}
        className="cpr-component-icon-picker-wrapper"
        onChange={onChange}
        value={value}
        renderToggle={({ isOpen, onToggle }) => {
          return (
            <Tooltip text={__('Icon Picker', 'cpr')}>
              <Icon
                className="cpr-component-icon-picker-button"
                aria-expanded={isOpen}
                onClick={onToggle}
                iconData={value}
                alwaysRender
              />
            </Tooltip>
          );
        }
        }
      />
    );
  }
}
