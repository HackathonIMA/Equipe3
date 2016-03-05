class CreateShares < ActiveRecord::Migration
  def change
    create_table :shares do |t|
      t.string :title
      t.string :description
      t.integer :category
      t.references :school, index: true, foreign_key: true
      t.timestamp :date
      t.string :icon

      t.timestamps null: false
    end
  end
end
