require "pry"
require "spec_helper"
require "./config.rb"

describe "New user experience" do
  before { goto TestConfig.url }

  describe "landing page" do 
    it "is visible" do
      expect(link(id: "signup")).to be_present
    end
  end

  describe "sign up" do 
    it "allows me to create an avatar and fill in my information" do
      link(id: "signup").click
      expect(div(id: "avatar")).to be_present
      elements(css: ".choice_select li:nth-child(2)").map(&:click) # the second of each thing
    end
  end
end