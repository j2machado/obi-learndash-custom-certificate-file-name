import CreatableSelect from "react-select/creatable";
import axios from 'axios';
const { render, useEffect, useState } = wp.element;

function App() {
  const [activeTab, setActiveTab] = useState("settings");
  const [selectedOptions, setSelectedOptions] = useState([]);
  const [unsavedOptions, setUnsavedOptions] = useState([]);
  const [isSaving, setIsSaving] = useState(false);

  const tabs = [
    { name: "settings", title: "Settings" },
  ];

  const options = [
    { value: 'option1', label: 'Course Name' },
    { value: 'option2', label: 'Course ID' },
    { value: 'option3', label: 'Certificate Name'},
    { value: 'option4', label: 'Certificate ID'},
    { value: 'option5', label: 'Completion Date'},
    { value: 'option6', label: 'User Display Name'},
    // Add more options as needed
  ];

  useEffect(() => {
    axios.get('/wp-json/obi-ld-cert/v1/settings', {
      headers: {
        'X-WP-Nonce': wpApiSettings.nonce
      }
    })
      .then(res => {
        // Assign the result directly as it's already in the correct format
        setSelectedOptions(res.data);
        setUnsavedOptions(res.data);
      })
      .catch(err => console.error(err));
  }, []);

  const handleChange = (optionObjects) => {
    optionObjects = optionObjects || [];
    setUnsavedOptions(optionObjects);
  };

  const handleSave = () => {
    setIsSaving(true);
    axios.post('/wp-json/obi-ld-cert/v1/settings', unsavedOptions, {
      headers: {
        'X-WP-Nonce': wpApiSettings.nonce
      }
    })
      .then(() => {
        setIsSaving(false);
        setSelectedOptions(unsavedOptions);
      })
      .catch(err => console.error(err));
  };


  return (
    <div>
      <header style={{ backgroundColor: "#eaeaea", width: "100%", padding: "10px 0 10px 25px" }}>
        <h1 style={{ textAlign: "left" }}>Obi LearnDash Custom Certificate File Name</h1>
      </header>
      <div style={{ display: "flex", justifyContent: "left", background: "lightblue", fontSize: "1.3em" }}>
        {tabs.map((tab) => (
          <button key={tab.name} onClick={() => setActiveTab(tab.name)} style={{ backgroundColor: activeTab === tab.name ? "#007cba" : "#f7f7f7", color: activeTab === tab.name ? "white" : "black", border: "none", padding: "10px 20px", cursor: "pointer" }}>
            {tab.title}
          </button>
        ))}
      </div>
      <div style={{ backgroundColor: "white", padding: "10px 0", filter: "drop-shadow(-10px 10px 20px rgba(0,0,0,0.1))" }}>
        {activeTab === "settings" && (
          <div style={{ padding: "25px 75px" }}>
            <h2>Settings</h2>
            <CreatableSelect
              isClearable
              isMulti
              onChange={handleChange}
              options={options}
              value={unsavedOptions}
            />
            <button type="button" className="button button-primary" onClick={handleSave} disabled={isSaving}>
              {isSaving ? 'Saving...' : 'Save Changes'}
            </button>
          </div>
        )}
      </div>
    </div>
  );
}

render(
  <App />,
  document.getElementById("obi-learndash-custom-certificate-file-name-options")
);
