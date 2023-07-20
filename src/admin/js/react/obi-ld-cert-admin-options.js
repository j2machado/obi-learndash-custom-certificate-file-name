import CreatableSelect from "react-select/creatable";
import Select from "react-select";
import axios from 'axios';
const { render, useEffect, useState } = wp.element;

function App() {
  const [selectedOptions, setSelectedOptions] = useState([]);
  const [unsavedOptions, setUnsavedOptions] = useState([]);
  const [selectedSeparator, setSelectedSeparator] = useState('');
  const [unsavedSeparator, setUnsavedSeparator] = useState('');
  const [isSaving, setIsSaving] = useState(false);

  const options = [
    { value: 'option1', label: 'Course Name' },
    { value: 'option2', label: 'Course ID' },
    { value: 'option3', label: 'Certificate Name'},
    { value: 'option4', label: 'Certificate ID'},
    { value: 'option5', label: 'Completion Date'},
    { value: 'option6', label: 'User Display Name'},
  ];

  const separators = [
    { value: ' ', label: 'Space' },
    { value: '_', label: 'Underscore' },
    { value: '-', label: 'Dash' },
  ];

  useEffect(() => {
    axios.get('/wp-json/obi-ld-cert/v1/settings', {
      headers: {
        'X-WP-Nonce': wpApiSettings.nonce
      }
    })
    .then(res => {
      setSelectedOptions(res.data);
      setUnsavedOptions(res.data);
    })
    .catch(err => console.error(err));

    axios.get('/wp-json/obi-ld-cert/v1/separator', {
      headers: {
        'X-WP-Nonce': wpApiSettings.nonce
      }
    })
    .then(res => {
      setSelectedSeparator(res.data);
      setUnsavedSeparator(res.data);
    })
    .catch(err => console.error(err));
  }, []);

  const handleChange = (optionObjects) => {
    optionObjects = optionObjects || [];
    setUnsavedOptions(optionObjects);
  };

  const handleSave = () => {
    setIsSaving(true);
    Promise.all([
      axios.post('/wp-json/obi-ld-cert/v1/settings', unsavedOptions, {
        headers: {
          'X-WP-Nonce': wpApiSettings.nonce
        }
      }),
      axios.post('/wp-json/obi-ld-cert/v1/separator', { separator: unsavedSeparator }, {
        headers: {
          'X-WP-Nonce': wpApiSettings.nonce
        }
      })
    ]).then(() => {
      setIsSaving(false);
      setSelectedOptions(unsavedOptions);
      setSelectedSeparator(unsavedSeparator);
    })
    .catch(err => console.error(err));
  };

  const handleSeparatorChange = (option) => {
    setUnsavedSeparator(option.value);
  };

  return (
    <div>
      <header style={{ backgroundColor: "#eaeaea", width: "100%", padding: "10px 0 10px 25px" }}>
        <h1 style={{ textAlign: "left" }}>Obi LearnDash Custom Certificate File Name</h1>
      </header>
      <div style={{ backgroundColor: "white", padding: "10px 0", filter: "drop-shadow(-10px 10px 20px rgba(0,0,0,0.1))" }}>
        <div style={{ padding: "25px 75px" }}>
          <h2>Settings</h2>
          <h3>Certificate Name Builder</h3>
          <CreatableSelect
            isClearable
            isMulti
            onChange={handleChange}
            options={options}
            value={unsavedOptions}
          />
          <h3>Choose Separator</h3>
          <Select
            onChange={handleSeparatorChange}
            options={separators}
            value={separators.find(option => option.value === unsavedSeparator)}
          />
          <button type="button" className="button button-primary" onClick={handleSave} disabled={isSaving}>
            {isSaving ? 'Saving...' : 'Save Changes'}
          </button>
        </div>
      </div>
    </div>
  );
}

render(
  <App />,
  document.getElementById("obi-learndash-custom-certificate-file-name-options")
);
