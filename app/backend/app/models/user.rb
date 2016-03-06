class User < ActiveRecord::Base
  has_many :shares
  has_many :interactions
  belongs_to :school

  def toggle_support!(share_id)
    interaction = Interaction.get_interaction(self.id, share_id)
    interaction.support |= false # default
    interaction.support = !interaction.support
    interaction.save()
  end

  def as_json(options = {})
    # options.merge!(:include => { :school => self.school })
    super({  }.merge(options || {}))
  end
end
