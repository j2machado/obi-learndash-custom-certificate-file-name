import CreatableSelect from "react-select/creatable";

const { render, useState } = wp.element;

const options = [
  { value: 'option1', label: 'Option 1' },
  { value: 'option2', label: 'Option 2' },
  // Add more options as needed
];

function MetaBoxApp() {
  const [selectedOptions, setSelectedOptions] = useState([]);
  const [useGlobalSettings, setUseGlobalSettings] = useState(true);

  const handleChange = (options) => {
    setSelectedOptions(options);
  };

  const handleCheckboxChange = (event) => {
    const isChecked = event.target.checked;
    setUseGlobalSettings(isChecked);
  };

  return (
    <div>
      <div style={{ backgroundColor: '#eeeeee', padding: '1em', marginBottom: '1em' }}>
        <p>Instruction text here.</p>
      </div>
      <input 
        type="checkbox" 
        checked={useGlobalSettings}
        onChange={handleCheckboxChange} 
      />
      <label>{useGlobalSettings ? 'Using Global Settings' : 'Using Custom Settings'}</label>
      {!useGlobalSettings && 
        <CreatableSelect
          isClearable
          isMulti
          onChange={handleChange}
          options={options}
          value={selectedOptions}
        />
      }
    </div>
  );
}

render(
  <MetaBoxApp />,
  document.getElementById("obi-custom-meta-box-id")
);
