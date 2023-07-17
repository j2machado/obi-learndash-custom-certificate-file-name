const { render, useState } = wp.element;

function App() {
  const [activeTab, setActiveTab] = useState("settings");

  const tabs = [
    { name: "settings", title: "Settings" },
  ];

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
            <p>Settings content will go here.</p>
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